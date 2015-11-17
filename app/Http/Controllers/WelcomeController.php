<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class WelcomeController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['home']]);
    }

    /**
     * Get the index page
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Get the home page
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function home()
    {
        return view('home');
    }
}
