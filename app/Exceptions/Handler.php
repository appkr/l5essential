<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        if (app()->environment('production') and
            ($e instanceof ModelNotFoundException
                or $e instanceof NotFoundHttpException)) {
            return response(view('errors.notice', [
                'title'       => trans('errors.not_found'),
                'description' => trans('errors.not_found_description')
            ]), $e->getStatusCode() ?: 404);
        }

        if (is_api_request()) {
            if ($e instanceof TokenExpiredException) {
                $message = 'token_expired';
            } else if ($e instanceof TokenInvalidException) {
                $message = 'token_invalid';
            } else if ($e instanceof JWTException) {
                $message = $e->getMessage() ?: 'could_not_create_token';
            } else if ($e instanceof Exception){
                $message = $e->getMessage() ?: 'Something broken :(';
            }

            $code = method_exists($e, 'getStatusCode')
                ? $e->getStatusCode()
                : $e->getCode();

            return response()->json([
                'code' => $code ?: 400,
                'errors' => $message,
            ], $code ?: 400);
        }

        return parent::render($request, $e);
    }
}
