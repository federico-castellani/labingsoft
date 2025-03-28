<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250326194330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add forecasts table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE forecasts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE forecasts (id INT NOT NULL, location_id INT NOT NULL, day TIMESTAMP(0) WITH TIME ZONE NOT NULL, short_description VARCHAR(255) NOT NULL, minimum_celsius_temperature INT DEFAULT NULL, maximum_celsius_temperature INT DEFAULT NULL, wind_speed_kmh INT DEFAULT NULL, humidity_percentage NUMERIC(3, 2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE forecasts ADD CONSTRAINT FK_6A95D6FA64D218E FOREIGN KEY (location_id) REFERENCES locations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6A95D6FA64D218E ON forecasts (location_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_forecast_by_location_and_day ON forecasts (location_id, day)');
        $this->addSql('COMMENT ON COLUMN forecasts.day IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE forecasts_id_seq CASCADE');
        $this->addSql('ALTER TABLE forecasts DROP CONSTRAINT FK_6A95D6FA64D218E');
        $this->addSql('DROP TABLE forecasts');
    }
}
