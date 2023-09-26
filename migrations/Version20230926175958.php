<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926175958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE founder DROP FOREIGN KEY FK_8F3C18AF49ABEB17');
        $this->addSql('ALTER TABLE founder ADD CONSTRAINT FK_8F3C18AF49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE founder DROP FOREIGN KEY FK_8F3C18AF49ABEB17');
        $this->addSql('ALTER TABLE founder ADD CONSTRAINT FK_8F3C18AF49ABEB17 FOREIGN KEY (band_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
