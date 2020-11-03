<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201103131554 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, customer_group_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_81398E09D2919A68 (customer_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter (id INT AUTO_INCREMENT NOT NULL, entity VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, route VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, is_default TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_7FC45F1DD1B862B8 (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_row (id INT AUTO_INCREMENT NOT NULL, filter_id INT NOT NULL, field VARCHAR(255) NOT NULL, operator VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, meta_data JSON DEFAULT NULL, INDEX IDX_63E47709D395B25E (filter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09D2919A68 FOREIGN KEY (customer_group_id) REFERENCES customer_group (id)');
        $this->addSql('ALTER TABLE filter_row ADD CONSTRAINT FK_63E47709D395B25E FOREIGN KEY (filter_id) REFERENCES filter (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09D2919A68');
        $this->addSql('ALTER TABLE filter_row DROP FOREIGN KEY FK_63E47709D395B25E');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_group');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE filter_row');
    }
}
