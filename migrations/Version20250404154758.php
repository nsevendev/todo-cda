<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404154758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mallo ALTER firstname TYPE VARCHAR(25)');
        $this->addSql('ALTER TABLE mallo ALTER lastname TYPE VARCHAR(25)');
        $this->addSql('ALTER TABLE mallo ALTER number TYPE INTEGER USING number::integer');
        $this->addSql('COMMENT ON COLUMN mallo.firstname IS \'(DC2Type:app_mallo_firstname)\'');
        $this->addSql('COMMENT ON COLUMN mallo.lastname IS \'(DC2Type:app_mallo_lastname)\'');
        $this->addSql('COMMENT ON COLUMN mallo.number IS \'(DC2Type:app_mallo_number)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mallo ALTER firstname TYPE VARCHAR(25)');
        $this->addSql('ALTER TABLE mallo ALTER lastname TYPE VARCHAR(25)');
        $this->addSql('ALTER TABLE mallo ALTER number TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN mallo.firstname IS NULL');
        $this->addSql('COMMENT ON COLUMN mallo.lastname IS NULL');
        $this->addSql('COMMENT ON COLUMN mallo.number IS NULL');
    }
}
