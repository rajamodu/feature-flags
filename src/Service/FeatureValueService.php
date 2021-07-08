<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FeatureValue;
use App\Repository\FeatureValueRepository;
use App\Service\Api\Request\FeatureValueRequest;

class FeatureValueService
{
    public function __construct(
        private FeatureValueRepository $featureValueRepository
    ) {
    }

    public function updateFeatureValue(FeatureValue $featureValue, FeatureValueRequest $featureValueRequest): FeatureValue
    {
        $featureValue->setEnabled($featureValueRequest->getEnabled());

        return $this->featureValueRepository->save($featureValue);
    }
}
