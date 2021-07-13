<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210713070223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created read_key & manage_key unique indexes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE1B7C4AFB ON project (read_key)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EEC6B72D19 ON project (manage_key)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE1B7C4AFB ON project');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EEC6B72D19 ON project');
    }
}
