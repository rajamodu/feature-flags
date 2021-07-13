<?php

declare(strict_types=1);

namespace App\Service\Manage\Serializer;

use App\Entity\Environment;
use App\Entity\Feature;
use App\Service\AbstractSerializer;

class FeatureSerializer extends AbstractSerializer
{
    public function serializeArray(array $data): array
    {
        $results = [];
        foreach ($data as $feature) {
            /** @var Feature $feature */
            $results[] = [
                'id' => $feature->getId(),
                'name' => $feature->getName(),
                'description' => $feature->getDescription(),
            ];
        }

        return $results;
    }

    public function serializeItem(Feature $feature): array
    {
        return [
            'id' => $feature->getId(),
            'name' => $feature->getName(),
            'description' => $feature->getDescription(),
            'values' => $this->serializeValues($feature),
        ];
    }

    private function serializeValues(Feature $feature): array
    {
        $results = [];
        foreach ($feature->getValues() as $featureValue) {
            $results[$featureValue->getEnvironment()->getName()] = $featureValue->isEnabled();
        }

        return $results;
    }
}
