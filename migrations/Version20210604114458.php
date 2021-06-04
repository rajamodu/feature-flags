<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210604114458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added unique indexes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX environment_unique ON environment (name, project_id)');
        $this->addSql('CREATE UNIQUE INDEX feature_unique ON feature (name, project_id)');
        $this->addSql('CREATE UNIQUE INDEX feature_value_unique ON feature_value (feature_id, environment_id)');
        $this->addSql('CREATE UNIQUE INDEX project_unique ON project (name, owner)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX environment_unique ON environment');
        $this->addSql('DROP INDEX feature_unique ON feature');
        $this->addSql('DROP INDEX feature_value_unique ON feature_value');
        $this->addSql('DROP INDEX project_unique ON project');
    }
}
