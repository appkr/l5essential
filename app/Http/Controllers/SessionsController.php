<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    /**
     * Create a new session controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);

        parent::__construct();
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * Handle login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (! \Auth::attempt($request->only('email', 'password'), $request->has('remember'))) {
            flash()->error(trans('auth.failed'));

            return back()->withErrors($validator)->withInput();
        }

        flash(trans('auth.welcome', ['name' => \Auth::user()->name]));

        return redirect(route('home'));
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        \Auth::logout();
        flash(trans('auth.goodbye'));

        return redirect(route('index'));
    }
}
