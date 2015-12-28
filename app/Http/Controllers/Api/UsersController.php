<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\UsersController as ParentController;
use App\User;
use Illuminate\Contracts\Validation\Validator;

class UsersController extends ParentController
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
     * Make a success response.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(User $user)
    {
        return response()->json([
            'code' => 201,
            'message' => 'success',
            'token' => \JWTAuth::fromUser($user),
        ], 201);
    }
}
