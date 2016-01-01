<?php

namespace Test\Http\Controllers;

class UsersController extends AuthTest
{
    /** @test */
    public function it_registers_a_user()
    {
        return $this->register(['name' => 'foo'])
            ->seePageIs(route('home'))
            ->seeInDatabase('users', ['name' => 'foo']);
    }

    /** @test */
    public function it_fails_registration_when_validation_fails()
    {
        $this->register([
                'name' => null,
                'email' => 'malformed.email',
                'password'  => 'short',
                'password_confirmation' => 'not.matching.password',
            ])
            ->see(trans('validation.required', ['attribute' => 'name']))
            ->see(trans('validation.email', ['attribute' => 'email']))
            ->see(trans('validation.confirmed', ['attribute' => 'password']))
            ->seePageIs(route('users.create'));
    }
}
