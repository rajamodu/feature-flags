<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Environment;
use App\Entity\Project;
use App\Repository\EnvironmentRepository;
use App\Service\EnvironmentService;
use App\Service\Manage\Request\EnvironmentRequest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EnvironmentServiceTest extends TestCase
{
    private EnvironmentService $environmentService;
    private EnvironmentRepository $environmentRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->environmentRepository
            = $this->getMockBuilder(EnvironmentRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->entityManager
            = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->environmentService = new EnvironmentService(
            $this->environmentRepository,
            $this->entityManager
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->environmentService,
            $this->environmentRepository,
            $this->entityManager
        );
    }

    public function testGetEnvironment(): void
    {
        $project = new Project();
        $environmentName = 'prod';

        $environment = new Environment();
        $environment->setName($environmentName);

        $this->environmentRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['project' => $project, 'name' => $environmentName])
            ->willReturn($environment)
        ;

        self::assertEquals($environment, $this->environmentService->getEnvironment($project, $environmentName));
    }

    public function testUpdateEnvironment(): void
    {
        $environmentName = 'new-environment';
        $environmentDescription = 'new-environment-description';

        $environmentRequest = $this->createEnvironmentRequest([
            'name' => $environmentName,
            'description' => $environmentDescription,
        ]);
        $environment = new Environment();

        $environment = $this->environmentService->updateEnvironment($environment, $environmentRequest);

        self::assertEquals($environmentName, $environment->getName());
        self::assertEquals($environmentDescription, $environment->getDescription());
    }

    private function createEnvironmentRequest(array $data): EnvironmentRequest
    {
        $request = new EnvironmentRequest();
        foreach ($data as $key => $value) {
            $reflectionProperty = new \ReflectionProperty(EnvironmentRequest::class, $key);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($request, $value);
        }

        return $request;
    }
}
