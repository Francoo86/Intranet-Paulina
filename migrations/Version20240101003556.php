<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240101003556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE show_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE show (id INT NOT NULL, guideline_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start TIME(0) WITHOUT TIME ZONE NOT NULL, finish TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_320ED901CC0B46A8 ON show (guideline_id)');
        $this->addSql('ALTER TABLE show ADD CONSTRAINT FK_320ED901CC0B46A8 FOREIGN KEY (guideline_id) REFERENCES guideline (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE publicity ADD show_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publicity ADD CONSTRAINT FK_9381276D0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9381276D0C1FC64 ON publicity (show_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE publicity DROP CONSTRAINT FK_9381276D0C1FC64');
        $this->addSql('DROP SEQUENCE show_id_seq CASCADE');
        $this->addSql('ALTER TABLE show DROP CONSTRAINT FK_320ED901CC0B46A8');
        $this->addSql('DROP TABLE show');
        $this->addSql('DROP INDEX IDX_9381276D0C1FC64');
        $this->addSql('ALTER TABLE publicity DROP show_id');
    }
}
