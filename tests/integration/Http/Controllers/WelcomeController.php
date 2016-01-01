<?php

namespace Test\Http\Controllers;

class WelcomeController extends \TestCase
{
    /** @test */
    public function it_loads_welcome_page()
    {
        $this->visit(route('index'))
            ->see('Hello');
    }

    /** @test */
    public function it_redirect_to_login_page_without_login()
    {
        $this->visit(route('home'))
            ->seePageIs(\Request::fullUrl());
    }
}
