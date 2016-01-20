<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        ValidationException::class,
        TokenExpiredException::class,
        TokenInvalidException::class,
        JWTException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e) and app()->environment('production')) {
            app(\App\Reporters\ErrorReport::class, [$e])->send();
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (app()->environment('production')) {
            $title = 'Error';
            $description = 'Unknown error occurred :(';
            $statusCode = 400;

            if ($e instanceof ModelNotFoundException or $e instanceof NotFoundHttpException) {
                $title = trans('errors.not_found');
                $description = trans('errors.not_found_description');
                $statusCode = 404;
            }

            return response(view('errors.notice', [
                'title'       => $title,
                'description' => $description
            ]), $e->getCode() ?: $statusCode);
        }

        if (is_api_request()) {
            $statusCode = method_exists($e, 'getStatusCode')
                ? $e->getStatusCode()
                : $e->getCode();

            if ($e instanceof TokenExpiredException) {
                $message = 'token_expired';
            } elseif ($e instanceof TokenInvalidException) {
                $message = 'token_invalid';
            } elseif ($e instanceof JWTException) {
                $message = $e->getMessage() ?: 'could_not_create_token';
            } elseif ($e instanceof NotFoundHttpException or $e instanceof ModelNotFoundException) {
                $statusCode = 404;
                $message = $e->getMessage() ?: 'not_found';
            } elseif ($e instanceof MethodNotAllowedHttpException) {
                $message = $e->getMessage() ?: 'not_allowed';
            } elseif ($e instanceof HttpResponseException){
                 return $e->getResponse();
            } elseif ($e instanceof Exception){
                $message = $e->getMessage() ?: 'Whoops~ Tell me what you did :(';
            }

            return json()->setStatusCode($statusCode ?: 400)->error($message);
        }

        return parent::render($request, $e);
    }
}
