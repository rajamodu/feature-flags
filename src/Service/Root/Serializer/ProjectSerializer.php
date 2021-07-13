<?php

declare(strict_types=1);

namespace App\Service\Root\Serializer;

use App\Entity\Feature;
use App\Entity\Project;
use App\Service\AbstractSerializer;

class ProjectSerializer extends AbstractSerializer
{
    public function serializeArray(array $data): array
    {
        $results = [];
        foreach ($data as $project) {
            /** @var Project $project */
            $results[] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'description' => $project->getDescription(),
                'owner' => $project->getOwner(),
            ];
        }

        return $results;
    }

    public function serializeItem(Project $project): array
    {
        return [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'owner' => $project->getOwner(),
            'read_key' => $project->getReadKey(),
            'manage_key' => $project->getManageKey(),
            'environments' => $this->serializeEnvironments($project),
            'features' => $this->serializeFeatures($project),
        ];
    }

    private function serializeEnvironments(Project $project): array
    {
        $results = [];

        foreach ($project->getEnvironments() as $environment) {
            $results[] = [
                'name' => $environment->getName(),
                'description' => $environment->getDescription(),
            ];
        }

        return $results;
    }

    private function serializeFeatures(Project $project): array
    {
        $results = [];
        foreach ($project->getFeatures() as $feature) {
            $results[] = [
                'name' => $feature->getName(),
                'description' => $feature->getDescription(),
                'values' => $this->serializeFeatureValues($feature),
            ];
        }

        return $results;
    }

    private function serializeFeatureValues(Feature $feature): array
    {
        $results = [];
        foreach ($feature->getValues() as $featureValue) {
            $results[$featureValue->getEnvironment()->getName()] = $featureValue->isEnabled();
        }

        return $results;
    }
}
