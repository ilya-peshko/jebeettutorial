<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930064827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAC39BEDB9');
        $this->addSql('DROP INDEX IDX_659DF2AAC39BEDB9 ON chat');
        $this->addSql('ALTER TABLE chat ADD user INT DEFAULT NULL, ADD room VARCHAR(255) NOT NULL, DROP user_from, DROP user_to');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA8D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA8D93D649 ON chat (user)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA8D93D649');
        $this->addSql('DROP INDEX IDX_659DF2AA8D93D649 ON chat');
        $this->addSql('ALTER TABLE chat ADD user_to INT DEFAULT NULL, DROP room, CHANGE user user_from INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAC39BEDB9 FOREIGN KEY (user_from) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAC39BEDB9 ON chat (user_from)');
    }
}
