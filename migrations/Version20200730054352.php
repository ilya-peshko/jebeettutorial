<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200730054352 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F41CD9E7A');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, email VARCHAR(254) NOT NULL, enabled TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE employer');
        $this->addSql('DROP INDEX IDX_4FBF094F41CD9E7A ON company');
        $this->addSql('ALTER TABLE company ADD user_id INT DEFAULT NULL, DROP employer_id');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094FA76ED395 ON company (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('CREATE TABLE employer (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(254) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enabled TINYINT(1) NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_login DATETIME DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_DE4CF066F85E0677 (username), UNIQUE INDEX UNIQ_DE4CF066E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_4FBF094FA76ED395 ON company');
        $this->addSql('ALTER TABLE company ADD employer_id INT NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F41CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F41CD9E7A ON company (employer_id)');
    }
}
