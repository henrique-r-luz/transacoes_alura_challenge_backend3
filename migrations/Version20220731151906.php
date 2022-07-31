<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220731151906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE import_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE import (id INT NOT NULL, data TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE transacao ADD import_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CEB6A263D9 FOREIGN KEY (import_id) REFERENCES import (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6C9E60CEB6A263D9 ON transacao (import_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transacao DROP CONSTRAINT FK_6C9E60CEB6A263D9');
        $this->addSql('DROP SEQUENCE import_id_seq CASCADE');
        $this->addSql('DROP TABLE import');
        $this->addSql('DROP INDEX IDX_6C9E60CEB6A263D9');
        $this->addSql('ALTER TABLE transacao DROP import_id');
    }
}
