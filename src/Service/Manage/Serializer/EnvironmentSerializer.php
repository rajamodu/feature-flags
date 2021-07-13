<?php

declare(strict_types=1);

namespace App\Service\Manage\Serializer;

use App\Entity\Environment;
use App\Service\AbstractSerializer;

class EnvironmentSerializer extends AbstractSerializer
{
    public function serializeItem(Environment $environment): array
    {
        return [
            'id' => $environment->getId(),
            'name' => $environment->getName(),
            'description' => $environment->getDescription(),
        ];
    }
}
