<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Entity\Environment;
use App\Entity\Feature;
use App\Entity\FeatureValue;
use App\Repository\FeatureValueRepository;
use App\Tests\DataFixtures\Repository\FeatureRepositoryFixture;
use App\Tests\DataFixtures\Repository\FeatureValueRepositoryFixture;
use App\Tests\Functional\FixtureWebTestCase;

class FeatureValueRepositoryTest extends FixtureWebTestCase
{
    /**
     * @var FeatureValueRepository
     */
    private $featureValueRepository;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->featureValueRepository = $container->get('doctrine')->getRepository(FeatureValue::class);
    }

    public function tearDown(): void
    {
        unset($this->featureValueRepository);

        parent::tearDown();
    }

    public function testFindOneByFeatureAndEnvironment(): void
    {
        /** @var Feature $feature */
        $feature = $this->getReference(FeatureRepositoryFixture::DEMO_FEATURE1_REF);

        /** @var Environment $environment */
        $environment = $this->getReference(FeatureRepositoryFixture::DEMO_ENV_PROD_REF);

        $featureValue = $this->featureValueRepository->findOneByFeatureAndEnvironment($feature, $environment);

        self::assertFalse($featureValue->isEnabled());
        self::assertEquals($feature, $featureValue->getFeature());
        self::assertEquals($environment, $featureValue->getEnvironment());
    }

    protected function getFixtures(): array
    {
        return [
            FeatureValueRepositoryFixture::class,
        ];
    }
}
