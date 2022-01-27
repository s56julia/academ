<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126232532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE post_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE post_translation (id INT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, content TEXT NOT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5829CF402C2AC5D3 ON post_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX post_translation_unique_translation ON post_translation (translatable_id, locale)');
        $this->addSql('ALTER TABLE post_translation ADD CONSTRAINT FK_5829CF402C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES symfony_demo_post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE symfony_demo_post DROP title');
        $this->addSql('ALTER TABLE symfony_demo_post DROP slug');
        $this->addSql('ALTER TABLE symfony_demo_post DROP summary');
        $this->addSql('ALTER TABLE symfony_demo_post DROP content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE post_translation_id_seq CASCADE');
        $this->addSql('DROP TABLE post_translation');
        $this->addSql('ALTER TABLE symfony_demo_post ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE symfony_demo_post ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE symfony_demo_post ADD summary VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE symfony_demo_post ADD content TEXT NOT NULL');
    }
}