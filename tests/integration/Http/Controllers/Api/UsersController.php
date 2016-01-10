<?php

namespace Test\Http\Controllers\Api;

use Teapot\StatusCode\All as StatusCode;

class UsersController extends ApiTest
{
    /** @test */
    public function it_respond_token_when_user_login()
    {
        $this->register()
            ->seeStatusCode(StatusCode::CREATED)
            ->seeJsonStructure(['success' => ['code', 'message'], 'meta' => ['token']]);
    }

    /** @test */
    public function it_fails_login_when_payload_malformed()
    {
        $this->register(['name' => '', 'email' => 'malformed.email', 'password' => 'short'])
            ->seeStatusCode(StatusCode::UNPROCESSABLE_ENTITY)
            ->seeJsonStructure(['error' => ['code', 'message' => [0, 1, 2]]]);
    }
}