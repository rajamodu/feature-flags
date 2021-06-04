<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
    }

    public function getProjectByNameAndOwner(string $name, string $owner): ?Project
    {
        return $this->projectRepository->findOneBy([
            'owner' => $owner,
            'name' => $name,
        ]);
    }
}
