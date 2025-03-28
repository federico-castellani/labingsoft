<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250326194109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique constraint to locations table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX unique_location_by_country_and_name ON locations (country, name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX unique_location_by_country_and_name');
    }
}
