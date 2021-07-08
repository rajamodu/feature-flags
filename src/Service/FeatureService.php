<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Environment;
use App\Entity\Feature;
use App\Entity\FeatureValue;
use App\Entity\Project;
use App\Repository\FeatureRepository;
use App\Service\Api\Request\FeatureRequest;

class FeatureService
{
    private const ERROR_VALUE_NOT_SET_FOR_ENV = 'Feature(%s) value is not set for environment(%s)';

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

    /**
     * @throws \Exception
     */
    public function getFeatureValue(Feature $feature, Environment $environment): FeatureValue
    {
        foreach ($feature->getValues() as $value) {
            if ($value->getEnvironment() === $environment) {
                return $value;
            }
        }

        throw new \Exception(self::ERROR_VALUE_NOT_SET_FOR_ENV, $feature->getId(), $feature->getName());
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
