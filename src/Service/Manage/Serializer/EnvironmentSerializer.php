<?php

declare(strict_types=1);

namespace App\Service\Manage\Serializer;

use App\Entity\Environment;

class EnvironmentSerializer
{
    public function serializeArray(array $data): array
    {
        $results = [];
        foreach ($data as $environment) {
            /** @var Environment $environment */
            $results[] = [
                'id' => $environment->getId(),
                'name' => $environment->getName(),
                'description' => $environment->getDescription(),
            ];
        }

        return $results;
    }

    public function serializeItem(Environment $environment): array
    {
        return [
            'id' => $environment->getId(),
            'name' => $environment->getName(),
            'description' => $environment->getDescription(),
            'features' => $this->serializeFeatures($environment),
        ];
    }

    private function serializeFeatures(Environment $environment): array
    {
        $results = [];
        foreach ($environment->getFeaturesValues() as $featureValue) {
            $results[$featureValue->getFeature()->getName()] = $featureValue->isEnabled();
        }

        return $results;
    }
}
