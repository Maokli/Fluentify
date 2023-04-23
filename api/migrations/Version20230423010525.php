<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423010525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE language_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quizz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_quizz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE language (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE quizz (id INT NOT NULL, language_id INT NOT NULL, category_id INT NOT NULL, questions VARCHAR(1000) NOT NULL, answers VARCHAR(1000) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C77973D82F1BAF4 ON quizz (language_id)');
        $this->addSql('CREATE INDEX IDX_7C77973D12469DE2 ON quizz (category_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, password_salt VARCHAR(255) NOT NULL, preferred_languages VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_quizz (id INT NOT NULL, quizz_id INT NOT NULL, owner_id INT NOT NULL, solved INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9EB56C65BA934BCD ON user_quizz (quizz_id)');
        $this->addSql('CREATE INDEX IDX_9EB56C657E3C61F9 ON user_quizz (owner_id)');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973D82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_quizz ADD CONSTRAINT FK_9EB56C65BA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_quizz ADD CONSTRAINT FK_9EB56C657E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE language_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quizz_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_quizz_id_seq CASCADE');
        $this->addSql('ALTER TABLE quizz DROP CONSTRAINT FK_7C77973D82F1BAF4');
        $this->addSql('ALTER TABLE quizz DROP CONSTRAINT FK_7C77973D12469DE2');
        $this->addSql('ALTER TABLE user_quizz DROP CONSTRAINT FK_9EB56C65BA934BCD');
        $this->addSql('ALTER TABLE user_quizz DROP CONSTRAINT FK_9EB56C657E3C61F9');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE quizz');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_quizz');
    }
}
