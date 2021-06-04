<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Environment;
use App\Entity\Project;
use App\Repository\EnvironmentRepository;

class EnvironmentService
{
    public function __construct(
        private EnvironmentRepository $environmentRepository
    ) {
    }

    public function getEnvironment(Project $project, string $environmentName): ?Environment
    {
        return $this->environmentRepository->findOneBy([
            'project' => $project,
            'name' => $environmentName,
        ]);
    }
}
