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
        parent::__construct();
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

    /**
     * Set locale preference of a user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function locale()
    {
        $cookie = cookie()->forever('locale__myProject', request('locale'));

        cookie()->queue($cookie);

        return ($return = request('return'))
            ? redirect(urldecode($return))->withCookie($cookie)
            : redirect(route('home'))->withCookie($cookie);
    }
}
