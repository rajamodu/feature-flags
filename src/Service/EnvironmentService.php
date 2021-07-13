<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Environment;
use App\Entity\Project;
use App\Repository\EnvironmentRepository;
use App\Service\Manage\Request\EnvironmentRequest;

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

    public function createEnvironment(Project $project, EnvironmentRequest $environmentRequest): Environment
    {
        $environment = new Environment();
        $environment
            ->setName($environmentRequest->getName())
            ->setDescription($environmentRequest->getDescription())
            ->setProject($project)
        ;

        return $this->environmentRepository->save($environment);
    }

    public function updateEnvironment(Environment $environment, EnvironmentRequest $environmentRequest): Environment
    {
        $environment
            ->setName($environmentRequest->getName())
            ->setDescription($environmentRequest->getDescription())
        ;

        return $this->environmentRepository->save($environment);
    }

    public function delete(Environment $environment): void
    {
        $this->environmentRepository->remove($environment);
    }
}
