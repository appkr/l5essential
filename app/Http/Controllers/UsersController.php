<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Create user controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');

        parent::__construct();
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($user = User::whereEmail($request->input('email'))->noPassword()->first()) {
            // Filter through the User model to find whether there is a social account
            // that has the same email address with the current request
            return $this->syncAccountInfo($request, $user);
        }

        return $this->createAccount($request);
    }

    /**
     * A user logged into the application with social account first,
     * and then, when s/he tries to register an application's native account,
     * update his/her name and password as given
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User                $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function syncAccountInfo(Request $request, User $user)
    {
        $validator = \Validator::make($request->except('_token'), [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user->update([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password'))
        ]);

        \Auth::login($user);
        flash(trans('auth.welcome', ['name' => $user->name]));

        return redirect(route('home'));
    }

    /**
     * A user tries to register a native account.
     * S/he haven't logged in to the application with a social account before.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function createAccount(Request $request)
    {
        $validator = \Validator::make($request->except('_token'), [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user = User::create($request->except('_token'));

        \Auth::login($user);
        flash(trans('auth.welcome', ['name' => $user->name]));

        return redirect(route('home'));
    }
}
