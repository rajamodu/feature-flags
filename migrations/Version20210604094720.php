<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210604094720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added environment description';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE environment ADD description LONGTEXT NOT NULL AFTER name');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE environment DROP description');
    }
}
