<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231229182238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE audience_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE balance_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE guideline_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE publicity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stock_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE summary_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE audience (id INT NOT NULL, demography VARCHAR(255) NOT NULL, locality VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE balance (id INT NOT NULL, stock_id INT DEFAULT NULL, amount INT NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ACF41FFEDCD6110 ON balance (stock_id)');
        $this->addSql('CREATE TABLE broadcaster (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE customer (id INT NOT NULL, name VARCHAR(255) NOT NULL, organisation VARCHAR(255) DEFAULT NULL, phone INT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE guideline (id INT NOT NULL, broadcaster_id INT DEFAULT NULL, manager_id INT DEFAULT NULL, show_name VARCHAR(255) NOT NULL, emission_number INT NOT NULL, creation_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1672B09A4848F12A ON guideline (broadcaster_id)');
        $this->addSql('CREATE INDEX IDX_1672B09A783E3463 ON guideline (manager_id)');
        $this->addSql('CREATE TABLE manager (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, balance_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, summary_id INT DEFAULT NULL, message TEXT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF5476CAAE91A3DD ON notification (balance_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA9395C3F3 ON notification (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF5476CA2AC2D45C ON notification (summary_id)');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, customer_id INT DEFAULT NULL, amount INT NOT NULL, method VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D28840D9395C3F3 ON payment (customer_id)');
        $this->addSql('CREATE TABLE publicity (id INT NOT NULL, audience_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, sentence TEXT NOT NULL, background VARCHAR(255) DEFAULT NULL, duration INT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9381276848CC616 ON publicity (audience_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9381276DCD6110 ON publicity (stock_id)');
        $this->addSql('CREATE INDEX IDX_93812769395C3F3 ON publicity (customer_id)');
        $this->addSql('CREATE TABLE report (id INT NOT NULL, manager_id INT DEFAULT NULL, publicity_id INT DEFAULT NULL, creation_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C42F7784783E3463 ON report (manager_id)');
        $this->addSql('CREATE INDEX IDX_C42F778466CB1996 ON report (publicity_id)');
        $this->addSql('CREATE TABLE stock (id INT NOT NULL, publicity_id INT DEFAULT NULL, time INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B36566066CB1996 ON stock (publicity_id)');
        $this->addSql('CREATE TABLE summary (id INT NOT NULL, customer_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CE2866639395C3F3 ON summary (customer_id)');
        $this->addSql('ALTER TABLE balance ADD CONSTRAINT FK_ACF41FFEDCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guideline ADD CONSTRAINT FK_1672B09A4848F12A FOREIGN KEY (broadcaster_id) REFERENCES broadcaster (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guideline ADD CONSTRAINT FK_1672B09A783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAAE91A3DD FOREIGN KEY (balance_id) REFERENCES balance (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA2AC2D45C FOREIGN KEY (summary_id) REFERENCES summary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE publicity ADD CONSTRAINT FK_9381276848CC616 FOREIGN KEY (audience_id) REFERENCES audience (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE publicity ADD CONSTRAINT FK_9381276DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE publicity ADD CONSTRAINT FK_93812769395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778466CB1996 FOREIGN KEY (publicity_id) REFERENCES publicity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B36566066CB1996 FOREIGN KEY (publicity_id) REFERENCES publicity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE summary ADD CONSTRAINT FK_CE2866639395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE audience_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE balance_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE customer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE guideline_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE publicity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE report_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stock_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE summary_id_seq CASCADE');
        $this->addSql('ALTER TABLE balance DROP CONSTRAINT FK_ACF41FFEDCD6110');
        $this->addSql('ALTER TABLE guideline DROP CONSTRAINT FK_1672B09A4848F12A');
        $this->addSql('ALTER TABLE guideline DROP CONSTRAINT FK_1672B09A783E3463');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAAE91A3DD');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA9395C3F3');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA2AC2D45C');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D9395C3F3');
        $this->addSql('ALTER TABLE publicity DROP CONSTRAINT FK_9381276848CC616');
        $this->addSql('ALTER TABLE publicity DROP CONSTRAINT FK_9381276DCD6110');
        $this->addSql('ALTER TABLE publicity DROP CONSTRAINT FK_93812769395C3F3');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784783E3463');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F778466CB1996');
        $this->addSql('ALTER TABLE stock DROP CONSTRAINT FK_4B36566066CB1996');
        $this->addSql('ALTER TABLE summary DROP CONSTRAINT FK_CE2866639395C3F3');
        $this->addSql('DROP TABLE audience');
        $this->addSql('DROP TABLE balance');
        $this->addSql('DROP TABLE broadcaster');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE guideline');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE publicity');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE summary');
    }
}
