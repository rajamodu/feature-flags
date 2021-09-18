<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Entity\Environment;
use App\Entity\Project;
use App\Repository\EnvironmentRepository;
use App\Tests\DataFixtures\Repository\EnvironmentRepositoryFixture;
use App\Tests\Functional\FixtureWebTestCase;

class EnvironmentRepositoryTest extends FixtureWebTestCase
{
    /**
     * @var EnvironmentRepository
     */
    private $environmentRepository;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->environmentRepository = $container->get('doctrine')->getRepository(Environment::class);
    }

    public function tearDown(): void
    {
        unset($this->environmentRepository);

        parent::tearDown();
    }

    public function testFindAllByProject(): void
    {
        /** @var Project $project */
        $project = $this->getReference(EnvironmentRepositoryFixture::DEMO_PROJECT_REF);
        $results = $this->environmentRepository->findAllByProject($project);

        self::assertCount(2, $results);

        self::assertEquals('prod', $results[0]->getName());
        self::assertEquals('stage', $results[1]->getName());
    }

    protected function getFixtures(): array
    {
        return [
            EnvironmentRepositoryFixture::class,
        ];
    }
}
