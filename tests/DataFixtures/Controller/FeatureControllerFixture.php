<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Controller;

use App\Tests\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;

class FeatureControllerFixture extends BaseFixture
{
    public const DEMO_PROJECT_REF = 'project_demo_ref';
    public const DEMO_PROJECT = 'demo';
    public const DEMO_READ_KEY = 'demo_read_key';
    public const DEMO_FEATURE1_REF = 'feature1_ref';
    public const DEMO_FEATURE1 = 'feature1';
    public const DEMO_ENV_PROD = 'prod';
    public const DEMO_ENV_PROD_REF = 'demo_prod_ref';

    public const PROJECT2_REF = 'project2_ref';
    public const PROJECT2 = 'project2';
    public const PROJECT2_READ_KEY = 'demo_read_key2';
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
            'manageKey' => 'demo_manage_key',
            'environments' => [
                [
                    'reference' => self::DEMO_ENV_PROD_REF,
                    'name' => self::DEMO_ENV_PROD,
                    'description' => 'Production environment',
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
            ],
        ],
        [
            'reference' => self::PROJECT2_REF,
            'name' => self::PROJECT2,
            'owner' => self::OWNER,
            'readKey' => self::PROJECT2_READ_KEY,
            'manageKey' => 'project2_manage_key',
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
