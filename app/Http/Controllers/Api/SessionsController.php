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
        return response()->json([
            'code' => 422,
            'errors' => $validator->errors()->all()
        ], 422);
    }

    /**
     * Make login failed response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondLoginFailed()
    {
        return response()->json([
            'code' => 401,
            'errors' => 'invalid_credentials',
        ], 401);
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
        return response()->json([
            'code' => 201,
            'message' => 'success',
            'token' => $token,
        ], 201);
    }
}
