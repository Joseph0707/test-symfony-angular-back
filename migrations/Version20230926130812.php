<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926130812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE band (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, city_id INT DEFAULT NULL, music_type_id INT DEFAULT NULL, group_name VARCHAR(255) NOT NULL, beginning_years INT NOT NULL, ending_years INT NOT NULL, members INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_48DFA2EBF92F3E70 (country_id), INDEX IDX_48DFA2EB8BAC62AF (city_id), INDEX IDX_48DFA2EB1B81EE8 (music_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE founder (id INT AUTO_INCREMENT NOT NULL, band_id INT DEFAULT NULL, INDEX IDX_8F3C18AF49ABEB17 (band_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music_type (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE band ADD CONSTRAINT FK_48DFA2EBF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE band ADD CONSTRAINT FK_48DFA2EB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE band ADD CONSTRAINT FK_48DFA2EB1B81EE8 FOREIGN KEY (music_type_id) REFERENCES music_type (id)');
        $this->addSql('ALTER TABLE founder ADD CONSTRAINT FK_8F3C18AF49ABEB17 FOREIGN KEY (band_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE band DROP FOREIGN KEY FK_48DFA2EBF92F3E70');
        $this->addSql('ALTER TABLE band DROP FOREIGN KEY FK_48DFA2EB8BAC62AF');
        $this->addSql('ALTER TABLE band DROP FOREIGN KEY FK_48DFA2EB1B81EE8');
        $this->addSql('ALTER TABLE founder DROP FOREIGN KEY FK_8F3C18AF49ABEB17');
        $this->addSql('DROP TABLE band');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE founder');
        $this->addSql('DROP TABLE music_type');
    }
}
