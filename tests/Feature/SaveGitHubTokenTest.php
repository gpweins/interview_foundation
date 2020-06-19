<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use App\User;

class SaveGitHubTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
    public function test_guest_cannot_save_data()
    {
        $response = $this->putJson('/save-token', [
            'token'=> '0123456789'
        ]);

        $response->assertStatus(401);
    }

    /**
     *
     * @return void
     */
    public function test_user_cannot_send_empty_token()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->putJson('/save-token', [
            'token' => ''
        ]);

        $response->assertStatus(422);
    }

    /**
     *
     * @return void
     */
    public function test_user_can_save_token()
    {
        // Removes the auth middleware as it uses the same
        // Crypt facade in the cookies
        $this->withoutMiddleware();

        $user = factory(User::class)->create();

        Crypt::shouldReceive('encrypt')
            ->once()
            ->with('0123456789')
            ->andReturn('encrypted_token');

        $response = $this->actingAs($user)->putJson('/save-token', [
            'token' => '0123456789'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'github_token' => 'encrypted_token',
        ]);

        $response->assertStatus(201);
    }

    /**
     *
     * @return void
     */
    public function test_authenticated_user_can_save_token()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->putJson('/save-token', [
            'token' => '0123456789'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
            'github_token' => null,
        ]);

        $response->assertStatus(201);
    }
}
