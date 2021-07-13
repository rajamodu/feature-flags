<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;

class AuthService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private string $rootToken
    ) {
    }

    public function getProjectByManageKey(): Project
    {
        return $this->projectRepository->findOneByManageKey(
            $this->getTokenFromGlobals()
        );
    }

    public function verifyReadAccessToken(Project $project): bool
    {
        $token = $this->getTokenFromGlobals();
        if (!$token) {
            return false;
        }

        return $project->getReadKey() === $this->getTokenFromGlobals();
    }

    public function getTokenFromGlobals(): ?string
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$token) {
            return null;
        }

        $token = str_replace('bearer', '', $token);
        $token = trim($token);

        return $token;
    }

    public function validateRootToken(string $token): bool
    {
        return $token === $this->rootToken;
    }
}
