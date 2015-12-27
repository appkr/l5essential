<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Validation\Validator;
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
            return $this->respondValidationError($validator);
        }

        $user->update([
            'name'     => $request->input('name'),
            'password' => bcrypt($request->input('password')),
        ]);

        $this->addMemberRole($user);

        return $this->respondCreated($user);
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
            return $this->respondValidationError($validator);
        }

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $this->addMemberRole($user);

        return $this->respondCreated($user);
    }

    /**
     * Attach Role to the user
     *
     * @param \App\User $user
     * @return array
     */
    protected function addMemberRole(User $user)
    {
        // 1 is admin, 2 is member
        return $user->roles()->sync([2]);
    }

    /**
     * Make validation error response.
     *
     * @param $validator
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondValidationError(Validator $validator)
    {
        return back()->withInput()->withErrors($validator);
    }

    /**
     * Make a success response.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated(User $user)
    {
        \Auth::login($user);
        flash(trans('auth.welcome', ['name' => $user->name]));

        return redirect(route('home'));
    }
}
