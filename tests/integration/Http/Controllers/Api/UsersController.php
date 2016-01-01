<?php

namespace Test\Http\Controllers\Api;

class UsersController extends ApiTest
{
    /** @test */
    public function it_respond_token_when_user_login()
    {
        $this->register()
            ->seeStatusCode(201)
            ->seeJsonStructure(['success' => ['code', 'message'], 'meta' => ['token']]);
    }

    /** @test */
    public function it_fails_login_when_payload_malformed()
    {
        $this->register(['name' => '', 'email' => 'malformed.email', 'password' => 'short'])
            ->seeStatusCode(422)
            ->seeJsonStructure(['error' => ['code', 'message' => [0, 1, 2]]]);
    }
}