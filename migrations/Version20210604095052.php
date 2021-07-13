<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210604095052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create demo project';
    }

    public function up(Schema $schema): void
    {
        // create project
        $this->connection->insert('project', [
            'name' => 'demo',
            'description' => 'Project for demonstration purposes',
            'owner' => 'antonshell',
            'read_key' => 'demo_read_key',
            'manage_key' => 'demo_manage_key',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $projectId = (int) $this->connection->lastInsertId();

        // create environments
        $environmentsIds = [];
        $this->connection->insert('environment', [
            'name' => 'prod',
            'description' => 'Production environment',
            'project_id' => $projectId,
        ]);
        $environmentsIds[] = $this->connection->lastInsertId();

        $this->connection->insert('environment', [
            'name' => 'stage',
            'description' => 'Staging environment',
            'project_id' => $projectId,
        ]);
        $environmentsIds[] = $this->connection->lastInsertId();

        $this->connection->insert('environment', [
            'name' => 'dev',
            'description' => 'Development environment',
            'project_id' => $projectId,
        ]);
        $environmentsIds[] = $this->connection->lastInsertId();

        // create features
        $featuresIds = [];
        $this->connection->insert('feature', [
            'name' => 'feature1',
            'description' => 'Demo project feature 1',
            'project_id' => $projectId,
        ]);
        $featuresIds[] = $this->connection->lastInsertId();

        $this->connection->insert('feature', [
            'name' => 'feature2',
            'description' => 'Demo project feature 2',
            'project_id' => $projectId,
        ]);
        $featuresIds[] = $this->connection->lastInsertId();

        foreach ($featuresIds as $featuresId) {
            foreach ($environmentsIds as $environmentsId) {
                $this->connection->insert('feature_value', [
                    'feature_id' => $featuresId,
                    'environment_id' => $environmentsId,
                    'enabled' => true,
                ]);
            }
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM feature_value');
        $this->addSql('DELETE FROM feature');
        $this->addSql('DELETE FROM environment');
        $this->addSql('DELETE FROM project');
    }
}
