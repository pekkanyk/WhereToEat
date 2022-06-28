<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220624175544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant_group (restaurant_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(restaurant_id, group_id))');
        $this->addSql('CREATE INDEX IDX_27DDF714B1E7706E ON restaurant_group (restaurant_id)');
        $this->addSql('CREATE INDEX IDX_27DDF714FE54D947 ON restaurant_group (group_id)');
        $this->addSql('ALTER TABLE restaurant_group ADD CONSTRAINT FK_27DDF714B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE restaurant_group ADD CONSTRAINT FK_27DDF714FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE restaurant_group');
    }
}
