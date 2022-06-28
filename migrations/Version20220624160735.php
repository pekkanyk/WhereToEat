<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220624160735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_restaurant (group_id INT NOT NULL, restaurant_id INT NOT NULL, PRIMARY KEY(group_id, restaurant_id))');
        $this->addSql('CREATE INDEX IDX_3C58738DFE54D947 ON group_restaurant (group_id)');
        $this->addSql('CREATE INDEX IDX_3C58738DB1E7706E ON group_restaurant (restaurant_id)');
        $this->addSql('ALTER TABLE group_restaurant ADD CONSTRAINT FK_3C58738DFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_restaurant ADD CONSTRAINT FK_3C58738DB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE group_restaurant');
    }
}
