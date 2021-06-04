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
            'read_key' => '0b6a0db7e751722d15ea4f28a7241bc13aba1c1ad8888fa038de313f2d8331209cd727171af8ac4f641296a9be91af42669037c5fd154996b057c3979b0d696d',
            'manage_key' => '19d48377d692d7921326df8eb1d4d105ca58069fb0fed45e308caffffd53ccab4c82e6013020eb7cd230eaab67ef06c6abb3095bcf8eac0d0aa40d4c01a71012',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $projectId = (int) $this->connection->lastInsertId();

        // create environments
        $environmentsIds = [];
        $environmentsIds[] = $this->connection->insert('environment', [
            'name' => 'prod',
            'description' => 'Production environment',
            'project_id' => $projectId,
        ]);

        $environmentsIds[] = $this->connection->insert('environment', [
            'name' => 'stage',
            'description' => 'Staging environment',
            'project_id' => $projectId,
        ]);

        $environmentsIds[] = $this->connection->insert('environment', [
            'name' => 'dev',
            'description' => 'Development environment',
            'project_id' => $projectId,
        ]);

        // create features
        $featuresIds = [];
        $featuresIds[] = $this->connection->insert('feature', [
            'name' => 'feature1',
            'description' => 'Demo project feature 1',
            'project_id' => $projectId,
        ]);

        $featuresIds[] = $this->connection->insert('feature', [
            'name' => 'feature2',
            'description' => 'Demo project feature 2',
            'project_id' => $projectId,
        ]);

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
