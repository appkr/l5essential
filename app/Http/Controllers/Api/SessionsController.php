<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SessionsController as ParentController;
use Illuminate\Contracts\Validation\Validator;

class SessionsController extends ParentController
{
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        // Todo Destroy the JWT of the current User.

        return response()->json([], 204);
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
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondLoginFailed($message)
    {
        return response()->json([
            'code' => 401,
            'errors' => $message
        ], 401);
    }

    /**
     * Make a success response.
     *
     * @param string $return
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($return = '')
    {
        // Todo Attach JWT.

        return response()->json([
            'code' => 201,
            'message' => 'success',
            'token' => 'token here',
        ], 201);
    }
}
