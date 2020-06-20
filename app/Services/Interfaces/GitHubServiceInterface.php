<?php

namespace App\Services\Interfaces;

interface GitHubServiceInterface
{
    /**
     * @param string $token
     * @return array
     */
    public function getStarredRepositories(string $token): array;
}
