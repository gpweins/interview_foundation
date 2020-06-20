<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Interfaces\GitHubServiceInterface;
use Github\Client as GithubClient;
use GrahamCampbell\GitHub\GitHubManager;
use Mockery;

class GitHubServiceTest extends TestCase
{
    private $starredRepos = [];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->starredRepos = require __DIR__ . '/../../Fixtures/starred_repositories.php';
    }

    /**
     * @return void
     */
    public function test_can_get_starred_repositories()
    {
        $client = $this->mock(GitHubClient::class, function ($mock) {
            return $mock->shouldReceive('currentUser->starring->all')
                ->once()
                ->andReturn($this->starredRepos);
        });

        $this->mock(GitHubManager::class, function ($mock) use ($client) {
            $mock->shouldReceive('getFactory->make')
                ->once()
                ->with([
                    'name'   => 'main',
                    'method' => 'token',
                    'token'  => 'encrypted_token',
                ])
                ->andReturn($client);
        });

        $service = app(GitHubServiceInterface::class);

        $repositories = $service->getStarredRepositories('encrypted_token');

        $this->assertCount(2, $repositories);
        $this->assertArraySubset([1 => ['full_name' => 'laravel/laravel']], $repositories);
    }
}
