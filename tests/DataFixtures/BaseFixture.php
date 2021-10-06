<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Tests\Factory\EntityFactory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixture extends AbstractFixture
{
    public function loadProjectsData(ObjectManager $objectManager, array $projectsData): void
    {
        $factory = new EntityFactory($objectManager);

        foreach ($projectsData as $projectRow) {
            $project = $factory->createProject([
                'name' => $projectRow['name'],
                'owner' => $projectRow['owner'],
                'readKey' => $projectRow['readKey'],
                'manageKey' => $projectRow['manageKey'],
            ]);
            $this->addReference($projectRow['reference'], $project);

            foreach ($projectRow['environments'] as $environmentRow) {
                $environment = $factory->createEnvironment([
                    'name' => $environmentRow['name'],
                    'description' => $environmentRow['description'],
                    'project' => $project,
                ]);
                $this->addReference($environmentRow['reference'], $environment);
            }

            foreach ($projectRow['features'] as $featureRow) {
                $feature = $factory->createFeature([
                    'name' => $featureRow['name'],
                    'description' => $featureRow['description'],
                    'project' => $project,
                ]);
                $this->addReference($featureRow['reference'], $feature);
            }

            foreach ($projectRow['values'] as $valueRow) {
                $factory->createFeatureValue([
                    'enabled' => $valueRow['enabled'],
                    'feature' => $this->getReference($valueRow['feature']),
                    'environment' => $this->getReference($valueRow['environment']),
                ]);
            }
        }
    }
}
