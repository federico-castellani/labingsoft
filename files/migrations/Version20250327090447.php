<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250327090447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add forecast table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE forecasts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE forecasts (id INT NOT NULL, location_id INT NOT NULL, name TIMESTAMP(0) WITH TIME ZONE NOT NULL, short_description VARCHAR(255) NOT NULL, wind_speed_kmh INT DEFAULT NULL, min_temp INT DEFAULT NULL, max_temp INT DEFAULT NULL, humidity_percentage INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A95D6FA64D218E ON forecasts (location_id)');
        $this->addSql('COMMENT ON COLUMN forecasts.name IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE forecasts ADD CONSTRAINT FK_6A95D6FA64D218E FOREIGN KEY (location_id) REFERENCES locations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE forecasts_id_seq CASCADE');
        $this->addSql('ALTER TABLE forecasts DROP CONSTRAINT FK_6A95D6FA64D218E');
        $this->addSql('DROP TABLE forecasts');
    }
}
