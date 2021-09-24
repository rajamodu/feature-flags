<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\FeatureValue;
use App\Service\FeatureValueService;
use App\Service\Manage\Request\FeatureValueRequest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FeatureValueServiceTest extends TestCase
{
    private FeatureValueService $featureValueService;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager
            = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->featureValueService = new FeatureValueService(
            $this->entityManager
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->featureValueService,
            $this->entityManager
        );
    }

    public function testUpdateFeatureValue(): void
    {
        $featureValueRequest = $this->createFeatureValueRequest([
            'environment' => 'prod',
            'enabled' => true,
        ]);

        $featureValue = new FeatureValue();
        $featureValue->setEnabled(false);

        $featureValue = $this->featureValueService->updateFeatureValue($featureValue, $featureValueRequest);
        self::assertTrue($featureValue->isEnabled());
    }

    private function createFeatureValueRequest(array $data): FeatureValueRequest
    {
        $request = new FeatureValueRequest();
        foreach ($data as $key => $value) {
            $reflectionProperty = new \ReflectionProperty(FeatureValueRequest::class, $key);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($request, $value);
        }

        return $request;
    }
}
