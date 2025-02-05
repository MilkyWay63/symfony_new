<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250202165947 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE appointments (id SERIAL NOT NULL, time_slot_id INT NOT NULL, client_name VARCHAR(255) NOT NULL, client_email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE38F844D62B0FA ON appointments (time_slot_id)');
        $this->addSql('CREATE TABLE psychologists (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FFC468E0E7927C74 ON psychologists (email)');
        $this->addSql('CREATE TABLE time_slots (id SERIAL NOT NULL, psychologist_id INT NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_booked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B3294AFE8EF269 ON time_slots (psychologist_id)');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_fe38f844d62b0fa FOREIGN KEY (time_slot_id) REFERENCES time_slots (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE time_slots ADD CONSTRAINT FK_1B3294AFE8EF269 FOREIGN KEY (psychologist_id) REFERENCES psychologists (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appointments DROP CONSTRAINT FK_fe38f844d62b0fa');
        $this->addSql('ALTER TABLE time_slots DROP CONSTRAINT FK_1B3294AFE8EF269');
        $this->addSql('DROP TABLE appointments');
        $this->addSql('DROP TABLE psychologists');
        $this->addSql('DROP TABLE time_slots');
    }
}
