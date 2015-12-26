<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    /**
     * Get the index page
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return response()->json([
            'name'    => 'myProject Api',
            'message' => 'Welcome to myProject Api. This is a base endpoint.',
            'version' => 'n/a',
            'links'   => [
                [
                    'rel'  => 'self',
                    'href' => route(\Route::currentRouteName())
                ],
                [
                    'rel'  => 'api.v1.index',
                    'href' => route('api.v1.index')
                ],
                [
                    'rel'  => 'api.v1.docs',
                    'href' => route('api.v1.docs')
                ],
            ],
        ]);
    }
}
