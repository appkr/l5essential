<?php

namespace Test\Http\Controllers\Api;

use Teapot\StatusCode\All as StatusCode;

class SessionsController extends ApiTest
{
    /** @test */
    public function it_respond_token_when_user_login()
    {
        $this->createUserStub()
            ->login()
            ->seeStatusCode(StatusCode::CREATED)
            ->seeJsonStructure(['success' => ['code', 'message'], 'meta' => ['token']]);
    }

    /** @test */
    public function it_fails_login_when_credentials_malformed()
    {
        $this->createUserStub()
            ->login(['email' => 'malformed.email', 'password' => 'short'])
            ->seeStatusCode(StatusCode::UNPROCESSABLE_ENTITY)
            ->seeJsonStructure(['error' => ['code', 'message' => [0, 1]]]);
    }

    /** @test */
    public function it_fails_login_when_credential_invalid()
    {
        $this->createUserStub()
            ->login(['password' => 'wrong.password'])
            ->seeStatusCode(StatusCode::UNAUTHORIZED)
            ->seeJsonContains(['message' => 'invalid_credentials']);
    }
}