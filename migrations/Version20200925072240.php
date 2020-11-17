<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925072240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AABF55763F');
        $this->addSql('DROP INDEX IDX_659DF2AABF55763F ON chat');
        $this->addSql('ALTER TABLE chat CHANGE userfrom user_from INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAC39BEDB9 FOREIGN KEY (user_from) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAC39BEDB9 ON chat (user_from)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAC39BEDB9');
        $this->addSql('DROP INDEX IDX_659DF2AAC39BEDB9 ON chat');
        $this->addSql('ALTER TABLE chat CHANGE user_from userFrom INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AABF55763F FOREIGN KEY (userFrom) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AABF55763F ON chat (userFrom)');
    }
}
