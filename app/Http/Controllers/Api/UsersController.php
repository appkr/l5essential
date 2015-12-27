<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\UsersController as ParentController;
use App\User;
use Illuminate\Contracts\Validation\Validator;

class UsersController extends ParentController
{
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
        // Todo Attach JWT.

        return response()->json([
            'code' => 201,
            'message' => 'success',
            'token' => 'token here',
        ], 201);
    }
}
