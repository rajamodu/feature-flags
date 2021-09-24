<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\ApiKeyGenerator;
use App\Service\ProjectService;
use App\Service\Root\Request\ProjectRequest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ProjectServiceTest extends TestCase
{
    private ProjectService $projectService;
    private ProjectRepository $projectRepository;
    private ApiKeyGenerator $apiKeyGenerator;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->projectRepository
            = $this->getMockBuilder(ProjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->apiKeyGenerator
            = $this->getMockBuilder(ApiKeyGenerator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->entityManager
            = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->projectService = new ProjectService(
            $this->projectRepository,
            $this->apiKeyGenerator,
            $this->entityManager
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->projectService,
            $this->projectRepository,
            $this->apiKeyGenerator,
            $this->entityManager
        );
    }

    public function testGetProjectByNameAndOwner(): void
    {
        $projectName = 'demo';
        $projectOwner = 'antonshell';

        $project = new Project();
        $project->setName($projectName);
        $project->setOwner($projectOwner);

        $this->projectRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['name' => $projectName, 'owner' => $projectOwner])
            ->willReturn($project)
        ;

        self::assertEquals($project, $this->projectService->getProjectByNameAndOwner($projectName, $projectOwner));
    }

    public function testUpdateProject(): void
    {
        $project = new Project();
        $project->setName('demo');
        $project->setDescription('demo-description');
        $project->setOwner('antonshell');

        $projectName = 'demo-new';
        $projectDescription = 'demo-description';
        $projectOwner = 'new-owner';
        $projectRequest = $this->createProjectRequest([
            'name' => $projectName,
            'description' => $projectDescription,
            'owner' => $projectOwner,
        ]);

        $this->entityManager
            ->expects(self::once())
            ->method('persist')
            ->with($project)
        ;

        $this->entityManager
            ->expects(self::once())
            ->method('flush')
        ;

        $project = $this->projectService->updateProject($project, $projectRequest);

        self::assertEquals($projectName, $project->getName());
        self::assertEquals($projectDescription, $project->getDescription());
        self::assertEquals($projectOwner, $project->getOwner());
    }

    private function createProjectRequest(array $data): ProjectRequest
    {
        $request = new ProjectRequest();
        foreach ($data as $key => $value) {
            $reflectionProperty = new \ReflectionProperty(ProjectRequest::class, $key);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($request, $value);
        }

        return $request;
    }
}
