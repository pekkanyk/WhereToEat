<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625130434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote ADD where_to_eat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564B71484BA FOREIGN KEY (where_to_eat_id) REFERENCES where_to_eat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A108564B71484BA ON vote (where_to_eat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vote DROP CONSTRAINT FK_5A108564B71484BA');
        $this->addSql('DROP INDEX IDX_5A108564B71484BA');
        $this->addSql('ALTER TABLE vote DROP where_to_eat_id');
    }
}
