<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Environment;
use App\Entity\FeatureValue;
use App\Entity\Project;
use App\Repository\EnvironmentRepository;
use App\Service\Manage\Request\EnvironmentRequest;
use Doctrine\ORM\EntityManagerInterface;

class EnvironmentService
{
    public function __construct(
        private EnvironmentRepository $environmentRepository,
        private EntityManagerInterface $entityManager,
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
        $this->entityManager->persist($environment);

        // create feature values
        foreach ($project->getFeatures() as $feature) {
            $featureValue = new FeatureValue();
            $featureValue
                ->setFeature($feature)
                ->setEnvironment($environment)
                ->setEnabled(true) // @TODO implement copying from other environment
            ;
            $this->entityManager->persist($featureValue);
        }

        $this->entityManager->flush();
        $this->entityManager->refresh($environment);

        return $environment;
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
        foreach ($environment->getFeaturesValues() as $featuresValue) {
            $this->entityManager->remove($featuresValue);
        }

        $this->entityManager->remove($environment);
        $this->entityManager->flush();
    }
}
