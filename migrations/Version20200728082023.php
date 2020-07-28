<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200728082023 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applicant (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, email VARCHAR(254) NOT NULL, enabled TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_CAAD1019F85E0677 (username), UNIQUE INDEX UNIQ_CAAD1019E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, employer_id INT NOT NULL, name VARCHAR(25) NOT NULL, address VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, INDEX IDX_4FBF094F41CD9E7A (employer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employer (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, email VARCHAR(254) NOT NULL, enabled TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_DE4CF066F85E0677 (username), UNIQUE INDEX UNIQ_DE4CF066E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F41CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id)');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE jobs ADD company_id INT NOT NULL, DROP company');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_A8936DC5979B1AD6 ON jobs (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5979B1AD6');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F41CD9E7A');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(254) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, enabled TINYINT(1) NOT NULL, last_login DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE applicant');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE employer');
        $this->addSql('DROP INDEX IDX_A8936DC5979B1AD6 ON jobs');
        $this->addSql('ALTER TABLE jobs ADD company VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP company_id');
    }
}
