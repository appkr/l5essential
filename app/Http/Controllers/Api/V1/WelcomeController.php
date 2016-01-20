<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    /**
     * Get the index page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return json([
            'name'    => 'myProject Api',
            'message' => 'Welcome to myProject Api. This is a base endpoint of version 1.',
            'version' => 'v1',
            'links'   => [
                [
                    'rel'  => 'self',
                    'href' => route(\Route::currentRouteName())
                ],
                [
                    'rel'  => 'api.users.store',
                    'href' => route('api.users.store')
                ],
                [
                    'rel'  => 'api.sessions.store',
                    'href' => route('api.sessions.store')
                ],
                [
                    'rel'  => 'api.v1.docs',
                    'href' => 'http://docs.forumv1.apiary.io/'
                ],
            ],
        ]);
    }
}
