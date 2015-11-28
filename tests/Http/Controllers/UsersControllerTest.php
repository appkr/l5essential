<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersControllerTest extends TestCase
{
    use DatabaseTransactions;
    use AuthTestTrait;

    /** @test */
    public function it_registers_a_user()
    {
        return $this->signUp(['name' => 'Homes'])
            ->seePageIs(route('home'))
            ->seeInDatabase('users', ['name' => 'Homes']);
    }

    /** @test */
    public function it_should_not_proceed_when_validation_fails()
    {
        $this->signUp([
                'name'                  => '',
                'email'                 => 'abc',
                'password'              => 'password',
                'password_confirmation' => 'secret',
            ])
            ->see(trans('validation.required', ['attribute' => 'name']))
            ->see(trans('validation.email', ['attribute' => 'email']))
            ->see(trans('validation.confirmed', ['attribute' => 'password']))
            ->seePageIs(route('users.create'));
    }
}
