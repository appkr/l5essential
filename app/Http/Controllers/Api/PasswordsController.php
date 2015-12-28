<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\PasswordsController as ParentController;

class PasswordsController extends ParentController
{
    public function __construct()
    {
        // Kill middleware defined by ParentController.
        $this->middleware = [];

        parent::__construct();
    }

    /**
     * Make an error response.
     *
     * @param     $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError($message, $statusCode = 400)
    {
        return response()->json([
            'code' => $statusCode,
            'errors' => $message
        ], $statusCode);
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
