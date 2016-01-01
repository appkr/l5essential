<?php

namespace Test\Http\Controllers\Api;

class SessionsController extends ApiTest
{
    /** @test */
    public function it_respond_token_when_user_login()
    {
        $this->createTestStub();

        $this->login()
            ->seeStatusCode(201)
            ->seeJsonStructure(['success' => ['code', 'message'], 'meta' => ['token']]);
    }

    /** @test */
    public function it_fails_login_when_credentials_malformed()
    {
        $this->createTestStub();

        $this->login(['email' => 'malformed.email', 'password' => 'short'])
            ->seeStatusCode(422)
            ->seeJsonStructure(['error' => ['code', 'message' => [0, 1]]]);
    }

    /** @test */
    public function it_fails_login_when_credential_invalid()
    {
        $this->createTestStub();

        $this->login(['password' => 'wrong.password'])
            ->seeStatusCode(401)
            ->seeJsonContains(['message' => 'invalid_credentials']);
    }
}