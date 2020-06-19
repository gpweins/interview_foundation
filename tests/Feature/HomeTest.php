<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_authenticated_user_can_see_welcome_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('Laravel');
        $response->assertSee('Home');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_see_home_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertSee('Laravel');
        $response->assertSee($user->name);
        $response->assertSee('Logout');
        $response->assertSee('github-token-form');

        $response->assertStatus(200);
    }
}
