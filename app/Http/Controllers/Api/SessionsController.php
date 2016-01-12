<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SessionsController as ParentController;
use Illuminate\Contracts\Validation\Validator;

class SessionsController extends ParentController
{
    public function __construct()
    {
        // Kill middleware defined by ParentController.
        $this->middleware = [];
        $this->middleware('throttle.api:10,1');

        parent::__construct();
    }

    /**
     * Make validation error response.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondValidationError(Validator $validator)
    {
        return json()->unprocessableError($validator->errors()->all());
    }

    /**
     * Make login failed response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondLoginFailed()
    {
        return json()->unauthorizedError('invalid_credentials');
    }

    /**
     * Make a success response.
     *
     * @param string $return
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($return = '', $token = '')
    {
        return json()->setMeta(['token' => $token])->created();
    }
}
