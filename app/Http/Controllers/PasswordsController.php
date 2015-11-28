<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Password;

class PasswordsController extends Controller
{
    /**
     * Create new password controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');

        parent::__construct();
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRemind()
    {
        return view('passwords.remind');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postRemind(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        if (User::whereEmail($request->input('email'))->noPassword()->first()) {
            // Notify the user if he/she is a social login user.
            flash()->errors(sprintf("%s %s", trans('auth.social_olny'), trans('auth.no_password')));

            return back();
        }

        $response = Password::sendResetLink($request->only('email'), function ($m) {
            $m->subject(trans('auth.email_password_reset_title'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                flash(trans($response));
                return back();

            case Password::INVALID_USER:
                flash()->error(trans($response));
                return back()->withInput();
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param null $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReset($token = null)
    {
        if (is_null($token) or strlen($token) != 64) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        return view('passwords.reset', compact('token'));
    }

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
            \Auth::login($user);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                flash(sprintf(
                    "%s %s",
                    trans($response),
                    trans('auth.welcome', ['name' => \Auth::user()->name])
                ));
                return redirect(route('home'));

            default:
                flash()->error(trans($response));
                return back()
                    ->withInput($request->only('email'));
        }
    }
}
