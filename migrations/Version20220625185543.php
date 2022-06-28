<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625185543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE where_to_eat DROP CONSTRAINT FK_18309C38D51E9150');
        $this->addSql('ALTER TABLE where_to_eat ADD CONSTRAINT FK_18309C38D51E9150 FOREIGN KEY (grp_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE where_to_eat DROP CONSTRAINT fk_18309c38d51e9150');
        $this->addSql('ALTER TABLE where_to_eat ADD CONSTRAINT fk_18309c38d51e9150 FOREIGN KEY (grp_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
