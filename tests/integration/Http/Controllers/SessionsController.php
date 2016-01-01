<?php

namespace Test\Http\Controllers;

class SessionsController extends AuthTest
{
    public function setUp()
    {
        parent::setUp();
        $this->createTestStub();
    }

    /** @test */
    public function it_logs_a_user_in()
    {
        $this->login()
            ->see(trans('auth.welcome', ['name' => $this->user->name]));
    }

    /** @test */
    public function it_fails_login_when_validation_fails()
    {
        $this->login(['email' => 'malformed.email', 'password' => 'short'])
            ->see(trans('validation.email', ['attribute' => 'email']))
            ->see(trans('validation.min.string', ['attribute' => 'password', 'min' => 6]))
            ->seePageIs(route('sessions.create'));
    }

    /** @test */
    public function it_fails_login_when_credentials_not_match()
    {
        $this->login(['password' => 'wrong.password'])
            ->see(trans('auth.failed'))
            ->seePageIs(route('sessions.create'));
    }

    /** @test */
    public function it_logs_a_user_out()
    {
        $this->actingAs($this->user)
            ->logout()
            ->seePageIs(route('index'));
    }
}