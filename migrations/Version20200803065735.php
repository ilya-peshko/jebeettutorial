<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200803065735 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_application (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, resume_id INT NOT NULL, viewed TINYINT(1) NOT NULL, INDEX IDX_C737C688BE04EA9 (job_id), INDEX IDX_C737C688D262AF09 (resume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resumes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, about_me LONGTEXT NOT NULL, views_count INT NOT NULL, INDEX IDX_CDB8AD33A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_application ADD CONSTRAINT FK_C737C688BE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE job_application ADD CONSTRAINT FK_C737C688D262AF09 FOREIGN KEY (resume_id) REFERENCES resumes (id)');
        $this->addSql('ALTER TABLE resumes ADD CONSTRAINT FK_CDB8AD33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE applicant');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_application DROP FOREIGN KEY FK_C737C688D262AF09');
        $this->addSql('CREATE TABLE applicant (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(254) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enabled TINYINT(1) NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_login DATETIME DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_CAAD1019F85E0677 (username), UNIQUE INDEX UNIQ_CAAD1019E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE job_application');
        $this->addSql('DROP TABLE resumes');
    }
}
