<?php

namespace Test\Http\Controllers\Api;

class PasswordsController extends ApiTest
{
    /** @test */
    public function it_can_remind_password()
    {
        $this->createTestStub();

        $this->remind()
            ->seeStatusCode(200)
            ->seeJsonContains(['message' => 'Success']);
    }

    /** @test */
    public function it_guides_social_user_password_reset_impossible()
    {
        $this->createTestStub(['password' => null]);

        $this->remind(['password' => null])
            ->seeStatusCode(400);
    }

    /** @test */
    public function it_fails_when_user_not_found()
    {
        $this->remind(['email' => 'not.existing@bar.com'])
            ->seeStatusCode(404);
    }
}