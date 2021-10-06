<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Model\ProjectReference;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthService
{
    public const ERROR_INVALID_CREDENTIALS = 'Invalid credentials provided';
    public const ERROR_MISSING_TOKEN = 'This action needs a valid token!';
    public const ERROR_MISSING_PROJECT_REFERENCE = 'This action needs a valid project reference!';
    public const ERROR_INVALID_TOKEN = 'Invalid access token provided';

    public function __construct(
        private ProjectRepository $projectRepository,
        private string $rootToken
    ) {
    }

    public function getProjectByManageKey(): Project
    {
        $token = $this->getTokenFromGlobals();
        $reference = $this->getProjectReferenceFromGlobals();
        if (!$reference) {
            throw new AccessDeniedHttpException(self::ERROR_MISSING_PROJECT_REFERENCE);
        }

        $project = $this->projectRepository->findOneByOwnerAndName(
            $reference->getOwner(),
            $reference->getName()
        );

        if (!$project || !password_verify($token, $project->getManageKey())) {
            throw new AccessDeniedHttpException(self::ERROR_INVALID_CREDENTIALS);
        }

        return $project;
    }

    public function verifyReadAccessToken(string $token, Project $project): bool
    {
        return password_verify($token, $project->getReadKey());
    }

    public function verifyManageAccessToken(string $token, Project $project): bool
    {
        return password_verify($token, $project->getManageKey());
    }

    public function getTokenFromGlobals(): ?string
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$token) {
            return null;
        }

        return trim(
            str_replace(['bearer', 'Bearer'], '', $token)
        );
    }

    public function validateRootToken(string $token): bool
    {
        return $token === $this->rootToken;
    }

    public function getProjectReferenceFromGlobals(): ?ProjectReference
    {
        $reference = $_SERVER['HTTP_PROJECT'] ?? null;
        if (!$reference) {
            return null;
        }
        $parts = explode('/', $reference);

        return new ProjectReference(
            $parts[0],
            $parts[1]
        );
    }
}
