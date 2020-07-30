<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200729100745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD image_name VARCHAR(255) DEFAULT NULL, ADD image_size INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE jobs CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE company DROP image_name, DROP image_size, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE jobs CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }
}
