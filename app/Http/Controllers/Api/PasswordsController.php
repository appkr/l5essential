<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\PasswordsController as ParentController;

class PasswordsController extends ParentController
{
    /**
     * Make an error response.
     *
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError($message)
    {
        return response()->json([
            'code' => 400,
            'errors' => $message
        ], 422);
    }

    /**
     * Make a success response.
     *
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondSuccess($message)
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
        ]);
    }
}
