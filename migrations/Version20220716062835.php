<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220716062835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE where_to_eat ADD winner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE where_to_eat ADD draw BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE where_to_eat ADD CONSTRAINT FK_18309C385DFCD4B8 FOREIGN KEY (winner_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_18309C385DFCD4B8 ON where_to_eat (winner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE where_to_eat DROP CONSTRAINT FK_18309C385DFCD4B8');
        $this->addSql('DROP INDEX IDX_18309C385DFCD4B8');
        $this->addSql('ALTER TABLE where_to_eat DROP winner_id');
        $this->addSql('ALTER TABLE where_to_eat DROP draw');
    }
}
