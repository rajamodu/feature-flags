<?php

declare(strict_types=1);

namespace App\Service\Manage\Serializer;

use App\Entity\Feature;
use App\Service\AbstractSerializer;

class FeatureSerializer extends AbstractSerializer
{
    public function serializeItem(Feature $feature): array
    {
        return [
            'id' => $feature->getId(),
            'name' => $feature->getName(),
            'description' => $feature->getDescription(),
        ];
    }
}
