<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211006164519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Encrypt read_key & manage_key for existing projects';
    }

    public function up(Schema $schema): void
    {
        $sql = 'SELECT * FROM project';
        $data = $this->connection
            ->prepare($sql)
            ->executeQuery()
            ->fetchAllAssociative()
        ;
        foreach ($data as $row) {
            $this->connection->update(
                'project',
                [
                    'read_key' => password_hash($row['read_key'], PASSWORD_BCRYPT),
                    'manage_key' => password_hash($row['manage_key'], PASSWORD_BCRYPT),
                ],
                [
                    'id' => $row['id']
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {

    }
}
