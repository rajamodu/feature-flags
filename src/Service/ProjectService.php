<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\Manage\Request\ProjectRequest;

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

    public function createProject(ProjectRequest $projectRequest): Project
    {
        $project = new Project();
        $project
            ->setName($projectRequest->getName())
            ->setDescription($projectRequest->getDescription())
            ->setOwner($projectRequest->getOwner())
            ->setManageKey('test') // @TODO implement keys generation
            ->setReadKey('test') // @TODO implement keys generation
        ;

        return $this->projectRepository->save($project);
    }

    public function updateProject(Project $project, ProjectRequest $projectRequest): Project
    {
        $project
            ->setName($projectRequest->getName())
            ->setDescription($projectRequest->getDescription())
            ->setOwner($projectRequest->getOwner())
        ;

        return $this->projectRepository->save($project);
    }

    public function delete(Project $project): void
    {
        $this->projectRepository->remove($project);
    }
}
