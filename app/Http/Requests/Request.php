<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Determine if the request is update
     *
     * @return bool
     */
    protected function isUpdate()
    {
        $needle = ['put', 'patch'];

        return in_array(strtolower($this->input('_method')), $needle)
            or in_array(strtolower($this->header('x-http-method-override')), $needle)
            or in_array(strtolower($this->method()), $needle);
    }

    /**
     * Determine if the request is delete
     *
     * @return bool
     */
    protected function isDelete()
    {
        $needle = ['delete'];

        return in_array(strtolower($this->input('_method')), $needle)
            or in_array(strtolower($this->header('x-http-method-override')), $needle)
            or in_array(strtolower($this->method()), $needle);
    }

    /**
     * {@inheritDoc}
     */
    public function response(array $errors)
    {
        if (is_api_request()) {
            return json()->unprocessableError($errors);
        }

        if ($this->ajax() || $this->wantsJson()) {
            return new JsonResponse($errors, 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }

    /**
     * {@inheritDoc}
     */
    public function forbiddenResponse()
    {
        if (is_api_request()) {
            return json()->forbiddenError();
        }

        return response('Forbidden', 403);
    }
}
