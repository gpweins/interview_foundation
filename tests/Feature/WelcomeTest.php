<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_guest_user_can_see_welcome_page()
    {
        $response = $this->get('/');

        $response->assertSee('Login');
        $response->assertSee('Register');
        $response->assertSee('Laravel');
        $response->assertStatus(200);
    }
}
