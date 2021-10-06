<?php

declare(strict_types=1);

namespace App\Service\Root\Serializer;

use App\Entity\Feature;
use App\Entity\Project;
use App\Model\ProjectCredentials;

class ProjectSerializer
{
    public function serializeArray(array $data): array
    {
        $results = [];
        foreach ($data as $project) {
            /* @var Project $project */
            $results[] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'description' => $project->getDescription(),
                'owner' => $project->getOwner(),
            ];
        }

        return $results;
    }

    public function serializeItem(Project $project, ?ProjectCredentials $projectCredentials = null): array
    {
        $data = [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'owner' => $project->getOwner(),
            'environments' => $this->serializeEnvironments($project),
            'features' => $this->serializeFeatures($project),
        ];

        if ($projectCredentials) {
            $data = array_merge($data, [
                'read_key' => $projectCredentials->getReadKey(),
                'manage_key' => $projectCredentials->getManageKey(),
            ]);
        }

        return $data;
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
