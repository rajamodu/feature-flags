<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Controller\Manage;

use App\Tests\Factory\EntityFactory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class EnvironmentControllerFixture extends AbstractFixture
{
    public const DEMO_PROJECT_REF = 'project_demo_ref';
    public const DEMO_PROJECT = 'demo';
    public const DEMO_READ_KEY = 'demo_read_key';
    public const DEMO_MANAGE_KEY = 'demo_manage_key';
    public const DEMO_FEATURE1_REF = 'feature1_ref';
    public const DEMO_FEATURE1 = 'feature1';
    public const DEMO_ENV_PROD = 'prod';
    public const DEMO_ENV_PROD_REF = 'demo_prod_ref';
    public const DEMO_ENV_STAGE = 'stage';
    public const DEMO_ENV_STAGE_REF = 'demo_stage_ref';

    public const PROJECT2_REF = 'project2_ref';
    public const PROJECT2 = 'project2';
    public const PROJECT2_READ_KEY = 'demo_read_key2';
    public const PROJECT2_MANAGE_KEY = 'demo_manage_key2';
    public const PROJECT2_FEATURE2_REF = 'feature2_ref';
    public const PROJECT2_FEATURE2 = 'feature2';
    public const PROJECT2_ENV_PROD = 'prod';
    public const PROJECT2_ENV_PROD_REF = 'project2_prod_ref';

    public const OWNER = 'antonshell';

    private array $projectsData = [
        [
            'reference' => self::DEMO_PROJECT_REF,
            'name' => self::DEMO_PROJECT,
            'owner' => self::OWNER,
            'readKey' => self::DEMO_READ_KEY,
            'manageKey' => self::DEMO_MANAGE_KEY,
            'environments' => [
                [
                    'reference' => self::DEMO_ENV_PROD_REF,
                    'name' => self::DEMO_ENV_PROD,
                    'description' => 'Production environment',
                ],
                [
                    'reference' => self::DEMO_ENV_STAGE_REF,
                    'name' => self::DEMO_ENV_STAGE,
                    'description' => 'Staging environment',
                ],
            ],
            'features' => [
                [
                    'reference' => self::DEMO_FEATURE1_REF,
                    'name' => self::DEMO_FEATURE1,
                    'description' => 'feature 1',
                ],
            ],
            'values' => [
                [
                    'enabled' => true,
                    'feature' => self::DEMO_FEATURE1_REF,
                    'environment' => self::DEMO_ENV_PROD_REF,
                ],
                [
                    'enabled' => true,
                    'feature' => self::DEMO_FEATURE1_REF,
                    'environment' => self::DEMO_ENV_STAGE_REF,
                ],
            ],
        ],
        [
            'reference' => self::PROJECT2_REF,
            'name' => self::PROJECT2,
            'owner' => self::OWNER,
            'readKey' => self::PROJECT2_READ_KEY,
            'manageKey' => self::PROJECT2_MANAGE_KEY,
            'environments' => [
                [
                    'reference' => self::PROJECT2_ENV_PROD_REF,
                    'name' => self::PROJECT2_ENV_PROD,
                    'description' => 'Production environment',
                ],
            ],
            'features' => [
                [
                    'reference' => self::PROJECT2_FEATURE2_REF,
                    'name' => self::PROJECT2_FEATURE2,
                    'description' => 'feature 2',
                ],
            ],
            'values' => [],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $objectManager): void
    {
        $factory = new EntityFactory($objectManager);

        foreach ($this->projectsData as $projectRow) {
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
