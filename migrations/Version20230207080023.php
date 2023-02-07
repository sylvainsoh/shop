<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207080023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE home_slider ADD COLUMN description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__home_slider AS SELECT id, title, button_message, button_url, image, is_displayed FROM home_slider');
        $this->addSql('DROP TABLE home_slider');
        $this->addSql('CREATE TABLE home_slider (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, button_message VARCHAR(255) NOT NULL, button_url VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, is_displayed BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO home_slider (id, title, button_message, button_url, image, is_displayed) SELECT id, title, button_message, button_url, image, is_displayed FROM __temp__home_slider');
        $this->addSql('DROP TABLE __temp__home_slider');
    }
}
