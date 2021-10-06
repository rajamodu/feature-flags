<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Tests\DataFixtures\Repository\ProjectRepositoryFixture;
use App\Tests\Functional\FixtureWebTestCase;

class ProjectRepositoryTest extends FixtureWebTestCase
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->projectRepository = $container->get('doctrine')->getRepository(Project::class);
    }

    public function tearDown(): void
    {
        unset($this->projectRepository);

        parent::tearDown();
    }

    public function testFindOneByOwnerAndName(): void
    {
        /** @var Project $expectedProject */
        $expectedProject = $this->getReference(ProjectRepositoryFixture::DEMO_PROJECT_REF);
        $project = $this->projectRepository->findOneByOwnerAndName(ProjectRepositoryFixture::OWNER, ProjectRepositoryFixture::DEMO_PROJECT);
        self::assertEquals($expectedProject->getId(), $project->getId());
    }

    protected function getFixtures(): array
    {
        return [
            ProjectRepositoryFixture::class,
        ];
    }
}
