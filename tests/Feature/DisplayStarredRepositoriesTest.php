<?php

namespace Tests\Feature;

use App\Services\GitHubService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class DisplayStarredRepositoriesTest extends TestCase
{
    use RefreshDatabase;

    private $starredRepos = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->starredRepos = require __DIR__ . '/../Fixtures/starred_repositories.php';
    }

    /**
     *
     * @return void
     */
    public function test_authenticated_user_can_get_starred_repositories(): void
    {
        $token = 'decrypted_token';
        $this->mock(GitHubService::class, function ($mock) use ($token) {
            $mock->shouldReceive('getStarredRepositories')
                ->once()
                ->with($token)
                ->andReturn($this->starredRepos);
        });

        // Removes the auth middleware as it uses the same
        // Crypt facade in the cookies
        $this->withoutMiddleware();

        $user = factory(User::class)->states('withToken')->create();
        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($user->github_token)
            ->andReturn($token);

        $response = $this->actingAs($user)->get('/github/starred');

        $response->assertSee('laravel\/laravel');
        $response->assertStatus(200);
    }

    /**
     *
     * @return void
     */
    public function test_authenticated_user_cannot_see_repositories_with_invalid_token(): void
    {
        $token = 'decrypted_token';

        // Removes the auth middleware as it uses the same
        // Crypt facade in the cookies
        $this->withoutMiddleware();

        $user = factory(User::class)->states('withToken')->create();
        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($user->github_token)
            ->andThrow(new DecryptException());

        $response = $this->actingAs($user)->get('/github/starred');

        $response->assertSee('Invalid credentials');
        $response->assertStatus(401);
    }

    /**
     *
     * @return void
     */
    public function test_github_service_throws_exception(): void
    {
        $token = 'decrypted_token';
        $this->mock(GitHubService::class, function ($mock) use ($token) {
            $mock->shouldReceive('getStarredRepositories')
                ->once()
                ->with($token)
                ->andThrow(new Exception());
        });

        // Removes the auth middleware as it uses the same
        // Crypt facade in the cookies
        $this->withoutMiddleware();

        $user = factory(User::class)->states('withToken')->create();
        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($user->github_token)
            ->andReturn($token);

        $response = $this->actingAs($user)->get('/github/starred');

        $response->assertSee('Failed to return starred repositories');
        $response->assertStatus(500);
    }
}
