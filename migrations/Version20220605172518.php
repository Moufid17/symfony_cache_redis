<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605172518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD user_from_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD user_to_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD datas TEXT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN payment.datas IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D20C3C701 FOREIGN KEY (user_from_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DD2F7B13D FOREIGN KEY (user_to_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6D28840D20C3C701 ON payment (user_from_id)');
        $this->addSql('CREATE INDEX IDX_6D28840DD2F7B13D ON payment (user_to_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D20C3C701');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840DD2F7B13D');
        $this->addSql('DROP INDEX IDX_6D28840D20C3C701');
        $this->addSql('DROP INDEX IDX_6D28840DD2F7B13D');
        $this->addSql('ALTER TABLE payment DROP user_from_id');
        $this->addSql('ALTER TABLE payment DROP user_to_id');
        $this->addSql('ALTER TABLE payment DROP datas');
    }
}
