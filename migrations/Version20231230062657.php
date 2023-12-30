<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230062657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicity ADD guideline_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publicity ADD CONSTRAINT FK_9381276CC0B46A8 FOREIGN KEY (guideline_id) REFERENCES guideline (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9381276CC0B46A8 ON publicity (guideline_id)');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT fk_c42f778466cb1996');
        $this->addSql('DROP INDEX idx_c42f778466cb1996');
        $this->addSql('ALTER TABLE report DROP publicity_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE publicity DROP CONSTRAINT FK_9381276CC0B46A8');
        $this->addSql('DROP INDEX IDX_9381276CC0B46A8');
        $this->addSql('ALTER TABLE publicity DROP guideline_id');
        $this->addSql('ALTER TABLE report ADD publicity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT fk_c42f778466cb1996 FOREIGN KEY (publicity_id) REFERENCES publicity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c42f778466cb1996 ON report (publicity_id)');
    }
}
