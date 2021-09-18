<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Entity\Feature;
use App\Entity\Project;
use App\Repository\FeatureRepository;
use App\Tests\DataFixtures\Repository\FeatureRepositoryFixture;
use App\Tests\Functional\FixtureWebTestCase;

class FeatureRepositoryTest extends FixtureWebTestCase
{
    /**
     * @var FeatureRepository
     */
    private $featureRepository;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->featureRepository = $container->get('doctrine')->getRepository(Feature::class);
    }

    public function tearDown(): void
    {
        unset($this->featureRepository);

        parent::tearDown();
    }

    public function testFindAllByProject(): void
    {
        /** @var Project $project */
        $project = $this->getReference(FeatureRepositoryFixture::DEMO_PROJECT_REF);
        $results = $this->featureRepository->findAllByProject($project);

        self::assertCount(2, $results);
        self::assertEquals('feature1', $results[0]->getName());
        self::assertEquals('feature3', $results[1]->getName());
    }

    protected function getFixtures(): array
    {
        return [
            FeatureRepositoryFixture::class,
        ];
    }
}
