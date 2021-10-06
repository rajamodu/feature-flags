<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Environment;
use App\Entity\Feature;
use App\Entity\FeatureValue;
use App\Entity\Project;

class EntityFactory extends AbstractFactory
{
    public function createProject(array $data = []): Project
    {
        $data = $this->addDefaults($data, [
            'name' => 'demo',
            'description' => 'demo project',
            'owner' => 'antonshell',
            'readKey' => bin2hex(random_bytes(64)),
            'manageKey' => bin2hex(random_bytes(64)),
        ]);

        $data['readKey'] = password_hash($data['readKey'], PASSWORD_BCRYPT);
        $data['manageKey'] = password_hash($data['manageKey'], PASSWORD_BCRYPT);

        return $this->create(Project::class, $data);
    }

    public function createEnvironment(array $data = []): Environment
    {
        $data = $this->addDefaults($data, [
            'name' => 'prod',
            'description' => 'Production environment',
        ]);

        if (!array_key_exists('project', $data)) {
            $project = $this->createProject();
            $data['project'] = $project;
            $data['project_id'] = $project->getId();
        }

        return $this->create(Environment::class, $data);
    }

    public function createFeature(array $data = []): Feature
    {
        $data = $this->addDefaults($data, [
            'name' => 'demo-feature',
            'description' => 'Demo feature',
        ]);

        if (!array_key_exists('project', $data)) {
            $project = $this->createProject();
            $data['project'] = $project;
            $data['project_id'] = $project->getId();
        }

        return $this->create(Feature::class, $data);
    }

    public function createFeatureValue(array $data = []): FeatureValue
    {
        $data = $this->addDefaults($data, [
            'enabled' => true,
        ]);

        if (!array_key_exists('feature', $data)) {
            $feature = $this->createFeature();
            $data['feature'] = $feature;
            $data['feature_id'] = $feature->getId();
        }

        if (!array_key_exists('environment', $data)) {
            $environment = $this->createEnvironment();
            $data['environment'] = $environment;
            $data['environment_id'] = $environment->getId();
        }

        return $this->create(FeatureValue::class, $data);
    }

    protected function addDefaults(array $data, array $defaults): array
    {
        return array_merge($defaults, $data);
    }
}
