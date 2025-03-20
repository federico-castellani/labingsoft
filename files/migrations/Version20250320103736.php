<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250320103736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create locations table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE locations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE locations (id INT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(2) NOT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE locations_id_seq CASCADE');
        $this->addSql('DROP TABLE locations');
    }
}
