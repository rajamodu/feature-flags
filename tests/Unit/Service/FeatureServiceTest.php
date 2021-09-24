<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Feature;
use App\Entity\Project;
use App\Repository\FeatureRepository;
use App\Service\FeatureService;
use App\Service\Manage\Request\FeatureRequest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FeatureServiceTest extends TestCase
{
    private FeatureService $featureService;
    private FeatureRepository $featureRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->featureRepository
            = $this->getMockBuilder(FeatureRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->entityManager
            = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->featureService = new FeatureService(
            $this->featureRepository,
            $this->entityManager
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->featureService,
            $this->featureRepository,
            $this->entityManager
        );
    }

    public function testGetFeature(): void
    {
        $project = new Project();
        $featureName = 'demo-feature';

        $feature = new Feature();
        $feature->setName($featureName);
        $feature->setProject($project);

        $this->featureRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['project' => $project, 'name' => $featureName])
            ->willReturn($feature)
        ;

        self::assertEquals($feature, $this->featureService->getFeature($project, $featureName));
    }

    public function testUpdateFeature(): void
    {
        $featureName = 'demo-feature-new';
        $featureDescription = 'demo-description-new';

        $featureRequest = $this->createFeatureRequest([
            'name' => $featureName,
            'description' => $featureDescription,
        ]);
        $feature = new Feature();

        $feature = $this->featureService->updateFeature($feature, $featureRequest);

        self::assertEquals($featureName, $feature->getName());
        self::assertEquals($featureDescription, $feature->getDescription());
    }

    private function createFeatureRequest(array $data): FeatureRequest
    {
        $request = new FeatureRequest();
        foreach ($data as $key => $value) {
            $reflectionProperty = new \ReflectionProperty(FeatureRequest::class, $key);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($request, $value);
        }

        return $request;
    }
}
