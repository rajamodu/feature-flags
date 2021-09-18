<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FeatureValue;
use App\Service\Manage\Request\FeatureValueRequest;
use Doctrine\ORM\EntityManagerInterface;

class FeatureValueService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function updateFeatureValue(FeatureValue $featureValue, FeatureValueRequest $featureValueRequest): FeatureValue
    {
        $featureValue->setEnabled($featureValueRequest->getEnabled());

        $this->entityManager->persist($featureValue);
        $this->entityManager->flush();

        return $featureValue;
    }
}
