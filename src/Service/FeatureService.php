<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Feature;
use App\Entity\FeatureValue;
use App\Entity\Project;
use App\Repository\FeatureRepository;
use App\Service\Manage\Request\FeatureRequest;
use Doctrine\ORM\EntityManagerInterface;

class FeatureService
{
    public function __construct(
        private FeatureRepository $featureRepository,
        private EntityManagerInterface $entityManager,
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
        $this->entityManager->persist($feature);

        // create feature values
        foreach ($project->getEnvironments() as $environment) {
            $featureValue = new FeatureValue();
            $featureValue
                ->setFeature($feature)
                ->setEnvironment($environment)
                ->setEnabled(true) // @TODO implement default value
            ;
            $this->entityManager->persist($featureValue);
        }

        $this->entityManager->flush();

        return $feature;
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
        foreach ($feature->getValues() as $featuresValue) {
            $this->entityManager->remove($featuresValue);
        }

        $this->entityManager->remove($feature);
        $this->entityManager->flush();
    }
}
