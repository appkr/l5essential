<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\PasswordsController as ParentController;

class PasswordsController extends ParentController
{
    public function __construct()
    {
        // Kill middleware defined by ParentController.
        // $this->middleware = [];
        $this->middleware('throttle.api:10,1');

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
        return json()->setStatusCode($statusCode)->error('not_found');
    }

    /**
     * Make a success response.
     *
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondSuccess($message)
    {
        return json()->success();
    }
}
