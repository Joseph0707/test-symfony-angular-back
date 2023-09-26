<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926170349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE country ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE founder ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE music_type ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE music_type DROP type');
        $this->addSql('ALTER TABLE founder DROP name');
        $this->addSql('ALTER TABLE city DROP name');
        $this->addSql('ALTER TABLE country DROP name');
    }
}
