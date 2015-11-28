<?php

trait AuthTestTrait
{
    public $loginPayload = [
        'email'                 => 'jane@example.com',
        'password'              => 'password'
    ];

    public $signupPayload = [
        'name'                  => 'Jane Doe',
        'email'                 => 'jane@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ];

    /**
     * Create a new user
     *
     * @return \App\User
     */
    public function createAccount()
    {
        return factory(App\User::class)->create([
            'email'    => $this->loginPayload['email'],
            'password' => bcrypt($this->loginPayload['password'])
        ]);
    }

    /**
     * Visit login page and attempt login
     *
     * @param array $overrides
     * @return mixed
     */
    public function logIn($overrides = [])
    {
        return $this->visit(route('sessions.create'))
            ->submitForm(trans('auth.button_login'), array_merge($this->loginPayload, $overrides));
    }

    /**
     * Visit login route
     *
     * @return mixed
     */
    public function logOut()
    {
        return $this->visit(route('sessions.destroy'));
    }

    /**
     * Visit signup page and attempt user registration
     *
     * @param array $overrides
     */
    public function signUp($overrides = [])
    {
        return $this->visit(route('users.create'))
            ->submitForm(trans('auth.button_signup'), array_merge($this->signupPayload, $overrides));
    }
}