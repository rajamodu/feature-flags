<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;

class AuthService
{
    public function verifyReadAccessToken(Project $project): bool
    {
        $token = $this->getTokenFromGlobals();
        if (!$token) {
            return false;
        }

        return $project->getReadKey() === $this->getTokenFromGlobals();
    }

    private function getTokenFromGlobals(): ?string
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$token) {
            return null;
        }

        $token = str_replace('bearer', '', $token);
        $token = trim($token);

        return $token;
    }
}
