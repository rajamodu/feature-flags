<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Environment;
use App\Entity\Feature;
use App\Entity\FeatureValue;
use App\Entity\Project;
use App\Enum\EnvironmentEnum;
use App\Enum\FeatureEnum;
use App\Model\ProjectCredentials;
use App\Repository\ProjectRepository;
use App\Service\Root\Request\ProjectRequest;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ApiKeyGenerator $apiKeyGenerator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getProjectByNameAndOwner(string $name, string $owner): ?Project
    {
        return $this->projectRepository->findOneBy([
            'owner' => $owner,
            'name' => $name,
        ]);
    }

    public function createProject(ProjectRequest $projectRequest, ProjectCredentials $projectCredentials): Project
    {
        // project
        $project = new Project();
        $project
            ->setName($projectRequest->getName())
            ->setDescription($projectRequest->getDescription())
            ->setOwner($projectRequest->getOwner())
            ->setManageKey(password_hash($projectCredentials->getManageKey(), PASSWORD_BCRYPT))
            ->setReadKey(password_hash($projectCredentials->getReadKey(), PASSWORD_BCRYPT))
        ;
        $this->entityManager->persist($project);

        // features
        $feature = new Feature();
        $feature
            ->setName(FeatureEnum::DEMO_FEATURE)
            ->setDescription('Feature for demonstration purposes')
            ->setProject($project)
        ;
        $this->entityManager->persist($feature);

        // environments
        $environmentsData = [
            [
                'name' => EnvironmentEnum::PROD,
                'description' => 'Production environment',
            ],
            [
                'name' => EnvironmentEnum::STAGE,
                'description' => 'Staging environment',
            ],
            [
                'name' => EnvironmentEnum::DEV,
                'description' => 'Development environment',
            ],
        ];

        foreach ($environmentsData as $environmentRow) {
            $environment = new Environment();
            $environment
                ->setName($environmentRow['name'])
                ->setDescription($environmentRow['description'])
                ->setProject($project)
            ;
            $this->entityManager->persist($environment);

            $featureValue = new FeatureValue();
            $featureValue
                ->setFeature($feature)
                ->setEnvironment($environment)
                ->setEnabled(true)
            ;
            $this->entityManager->persist($featureValue);
        }

        $this->entityManager->flush();

        $this->entityManager->refresh($project);
        foreach ($project->getFeatures() as $feature) {
            $this->entityManager->refresh($feature);
        }

        return $project;
    }

    public function createCredentials(): ProjectCredentials
    {
        return new ProjectCredentials(
            $this->apiKeyGenerator->generateApiKey(),
            $this->apiKeyGenerator->generateApiKey()
        );
    }

    public function updateProject(Project $project, ProjectRequest $projectRequest): Project
    {
        $project
            ->setName($projectRequest->getName())
            ->setDescription($projectRequest->getDescription())
            ->setOwner($projectRequest->getOwner())
        ;

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function delete(Project $project): void
    {
        foreach ($project->getEnvironments() as $environment) {
            $this->entityManager->remove($environment);
        }

        foreach ($project->getFeatures() as $feature) {
            foreach ($feature->getValues() as $featureValue) {
                $this->entityManager->remove($featureValue);
            }

            $this->entityManager->remove($feature);
        }

        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }
}
