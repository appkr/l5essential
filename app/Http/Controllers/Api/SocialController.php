<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SocialController as ParentController;
use App\User;

class SocialController extends ParentController
{
    /**
     * Make a success response.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(User $user)
    {
        // Todo Attach JWT.
        // Todo Fire event('users.login', [$user])

        return response()->json([
            'code' => 201,
            'message' => 'success',
            'token' => 'token here',
        ], 201);
    }
}
