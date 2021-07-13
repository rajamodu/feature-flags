<?php

declare(strict_types=1);

namespace App\Service\Root\Serializer;

use App\Entity\Project;
use App\Service\AbstractSerializer;

class ProjectSerializer extends AbstractSerializer
{
    public function serializeItem(Project $project): array
    {
        return [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'owner' => $project->getOwner(),
            'read_key' => $project->getReadKey(),
            'manage_key' => $project->getManageKey(),
        ];
    }
}
