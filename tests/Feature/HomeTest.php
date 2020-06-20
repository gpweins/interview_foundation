<?php

namespace Tests\Feature;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use App\User;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
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

    /**
     *
     * @return void
     */
    public function test_user_can_see_decrypted_token()
    {
        // Removes the auth middleware as it uses the same
        // Crypt facade in the cookies
        $this->withoutMiddleware();

        $user = factory(User::class)->states('withToken')->create();

        $token = '0123456789';
        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($user->github_token)
            ->andReturn($token);

        $response = $this->actingAs($user)->get('/home');

        $response->assertSee('Laravel');
        $response->assertSee($user->name);
        $response->assertSee('Logout');
        $response->assertSee('github-token-form');
        $response->assertSee($token);

        $response->assertStatus(200);

    }

    /**
     *
     * @return void
     */
    public function test_decryption_throws_exception()
    {
        // Removes the auth middleware as it uses the same
        // Crypt facade in the cookies
        $this->withoutMiddleware();

        $user = factory(User::class)->states('withToken')->create();

        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($user->github_token)
            ->andThrow(new DecryptException());

        $response = $this->actingAs($user)->get('/home');

        $response->assertSee('Laravel');
        $response->assertSee($user->name);
        $response->assertSee('Logout');
        $response->assertSee('github-token-form');

        $response->assertStatus(200);
    }
}
