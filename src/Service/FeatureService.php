<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Feature;
use App\Entity\Project;
use App\Repository\FeatureRepository;
use App\Service\Manage\Request\FeatureRequest;

class FeatureService
{
    public function __construct(
        private FeatureRepository $featureRepository
    ) {
    }

    public function getFeature(Project $project, string $featureName): ?Feature
    {
        return $this->featureRepository->findOneBy([
            'project' => $project,
            'name' => $featureName,
        ]);
    }

    public function createFeature(Project $project, FeatureRequest $featureRequest): Feature
    {
        $feature = new Feature();
        $feature
            ->setName($featureRequest->getName())
            ->setDescription($featureRequest->getDescription())
            ->setProject($project)
        ;

        return $this->featureRepository->save($feature);
    }

    public function updateFeature(Feature $feature, FeatureRequest $featureRequest)
    {
        $feature
            ->setName($featureRequest->getName())
            ->setDescription($featureRequest->getDescription())
        ;

        return $this->featureRepository->save($feature);
    }

    public function delete(Feature $feature): void
    {
        $this->featureRepository->remove($feature);
    }
}
