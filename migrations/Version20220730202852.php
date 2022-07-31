<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220730202852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE conta_bancaria_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transacao_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE conta_bancaria (id INT NOT NULL, nome_banco VARCHAR(255) NOT NULL, agencia VARCHAR(255) NOT NULL, conta VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_conta_bancaria ON conta_bancaria (nome_banco, agencia, conta)');
        $this->addSql('CREATE TABLE transacao (id INT NOT NULL, conta_bancaria_origem_id INT DEFAULT NULL, conta_bancaria_destino_id INT DEFAULT NULL, valor NUMERIC(8, 2) NOT NULL, data TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6C9E60CE2AEDBB3B ON transacao (conta_bancaria_origem_id)');
        $this->addSql('CREATE INDEX IDX_6C9E60CEF7F194C9 ON transacao (conta_bancaria_destino_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_conta_origem_conta_destino_data ON transacao (conta_bancaria_origem_id, conta_bancaria_destino_id, data)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CE2AEDBB3B FOREIGN KEY (conta_bancaria_origem_id) REFERENCES conta_bancaria (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CEF7F194C9 FOREIGN KEY (conta_bancaria_destino_id) REFERENCES conta_bancaria (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transacao DROP CONSTRAINT FK_6C9E60CE2AEDBB3B');
        $this->addSql('ALTER TABLE transacao DROP CONSTRAINT FK_6C9E60CEF7F194C9');
        $this->addSql('DROP SEQUENCE conta_bancaria_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transacao_id_seq CASCADE');
        $this->addSql('DROP TABLE conta_bancaria');
        $this->addSql('DROP TABLE transacao');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
