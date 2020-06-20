<?php

namespace App\Services;

use App\Services\Interfaces\GitHubServiceInterface;
use GrahamCampbell\GitHub\GitHubManager;

class GitHubService implements GitHubServiceInterface
{
    private $github;

    public function __construct(GitHubManager $github)
    {
        $this->github = $github;
    }

    public function getStarredRepositories(string $token): array
    {
        $client = $this->github->getFactory()->make([
            'name'   => 'main',
            'method' => 'token',
            'token'  => $token,
        ]);

        return $client->currentUser()->starring()->all();
    }
}
