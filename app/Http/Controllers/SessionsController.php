<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Contracts\Validation\Validator;
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
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->respondValidationError($validator);
        }

        $token = is_api_request()
            ? \JWTAuth::attempt($request->only('email', 'password'))
            : Auth::attempt($request->only('email', 'password'), $request->has('remember'));

        if (! $token) {
            return $this->respondLoginFailed();
        }

        event('users.login', [Auth::user()]);

        return $this->respondCreated($request->input('return'), $token);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        Auth::logout();
        flash(trans('auth.goodbye'));

        return redirect(route('index'));
    }

    /**
     * Make validation error response.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondValidationError(Validator $validator)
    {
        return back()->withInput()->withErrors($validator);
    }

    /**
     * Make login failed response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondLoginFailed()
    {
        flash()->error(trans('auth.failed'));

        return back()->withInput();
    }

    /**
     * Make a success response.
     *
     * @param string $return
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($return = '', $token = '')
    {
        flash(trans('auth.welcome', ['name' => Auth::user()->name]));

        return ($return)
            ? redirect(urldecode($return))
            : redirect()->intended();
    }
}
