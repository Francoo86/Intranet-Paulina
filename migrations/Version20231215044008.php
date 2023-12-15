<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215044008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE summary_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE summary (id INT NOT NULL, customer_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CE2866639395C3F3 ON summary (customer_id)');
        $this->addSql('ALTER TABLE summary ADD CONSTRAINT FK_CE2866639395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD summary_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA2AC2D45C FOREIGN KEY (summary_id) REFERENCES summary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BF5476CA9395C3F3 ON notification (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF5476CA2AC2D45C ON notification (summary_id)');
        $this->addSql('ALTER TABLE payment ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6D28840D9395C3F3 ON payment (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA2AC2D45C');
        $this->addSql('DROP SEQUENCE summary_id_seq CASCADE');
        $this->addSql('ALTER TABLE summary DROP CONSTRAINT FK_CE2866639395C3F3');
        $this->addSql('DROP TABLE summary');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA9395C3F3');
        $this->addSql('DROP INDEX IDX_BF5476CA9395C3F3');
        $this->addSql('DROP INDEX UNIQ_BF5476CA2AC2D45C');
        $this->addSql('ALTER TABLE notification DROP customer_id');
        $this->addSql('ALTER TABLE notification DROP summary_id');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D9395C3F3');
        $this->addSql('DROP INDEX IDX_6D28840D9395C3F3');
        $this->addSql('ALTER TABLE payment DROP customer_id');
    }
}
