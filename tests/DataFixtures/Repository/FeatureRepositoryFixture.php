<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Repository;

use App\Tests\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;

class FeatureRepositoryFixture extends BaseFixture
{
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
                [
                    'reference' => self::DEMO_FEATURE3_REF,
                    'name' => self::DEMO_FEATURE3,
                    'description' => 'feature 3',
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
                [
                    'enabled' => true,
                    'feature' => self::DEMO_FEATURE3_REF,
                    'environment' => self::DEMO_ENV_PROD_REF,
                ],
                [
                    'enabled' => true,
                    'feature' => self::DEMO_FEATURE3_REF,
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
        $this->loadProjectsData($objectManager, $this->projectsData);
    }
}
