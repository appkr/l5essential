<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class SessionsControllerTest extends TestCase
{
    use DatabaseTransactions;
    use AuthTestTrait;

    /** @test */
    public function it_should_not_proceed_when_validation_fails()
    {
        $this->logIn(['email' => 'abc', 'password' => ''])
            ->see(trans('validation.email', ['attribute' => 'email']))
            ->see(trans('validation.required', ['attribute' => 'password']))
            ->seePageIs(route('sessions.create'));
    }

    /** @test */
    public function it_should_fails_login_when_credentials_not_match()
    {
        $user = $this->createAccount();

        $this->logIn(['password' => 'secret'])
            ->see(trans('auth.failed'))
            ->seePageIs(route('sessions.create'));
    }

    /** @test */
    public function it_logs_a_user_in()
    {
        $user = $this->createAccount();

        $this->logIn()
            ->see(trans('auth.welcome', ['name' => $user->name]));
    }

    /** @test */
    public function it_logs_a_user_out()
    {
        $user = $this->createAccount();

        $this->actingAs($user)
            ->logOut()
            ->seePageIs(route('index'));
    }
}