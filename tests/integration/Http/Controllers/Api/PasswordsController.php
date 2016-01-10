<?php

namespace Test\Http\Controllers\Api;

use Teapot\StatusCode\All as StatusCode;

class PasswordsController extends ApiTest
{
    /** @test */
    public function it_can_remind_password()
    {
        $this->createUserStub()
            ->remind()
            ->seeStatusCode(StatusCode::OK)
            ->seeJsonContains(['message' => 'Success']);
    }

    /** @test */
    public function it_guides_social_user_password_reset_impossible()
    {
        $this->createUserStub(['password' => null])
            ->remind(['password' => null])
            ->seeStatusCode(StatusCode::BAD_REQUEST);
    }

    /** @test */
    public function it_fails_when_user_not_found()
    {
        $this->remind(['email' => 'not.existing@bar.com'])
            ->seeStatusCode(StatusCode::NOT_FOUND);
    }
}