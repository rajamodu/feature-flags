<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210604083810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added created_at and updated_at fields to entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE environment ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE feature ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE feature_value ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE project ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE environment DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE feature DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE feature_value DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE project DROP created_at, DROP updated_at');
    }
}
