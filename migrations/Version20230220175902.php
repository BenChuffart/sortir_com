<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220175902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, postal_code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_741D53CD8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trips (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, campus_id INT NOT NULL, status_id INT NOT NULL, place_id INT NOT NULL, name VARCHAR(255) NOT NULL, start_date_time DATETIME NOT NULL, duration INT NOT NULL, deadline DATE NOT NULL, max_registration INT NOT NULL, informations LONGTEXT DEFAULT NULL, INDEX IDX_7656F53B61220EA6 (creator_id), INDEX IDX_7656F53BAF5D55E1 (campus_id), INDEX IDX_7656F53B6BF700BD (status_id), INDEX IDX_7656F53BDA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, campus_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, phone VARCHAR(10) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649AF5D55E1 (campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_trip (user_id INT NOT NULL, trip_id INT NOT NULL, INDEX IDX_CD7B9F2A76ED395 (user_id), INDEX IDX_CD7B9F2A5BC2E0E (trip_id), PRIMARY KEY(user_id, trip_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE trips ADD CONSTRAINT FK_7656F53B61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trips ADD CONSTRAINT FK_7656F53BAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE trips ADD CONSTRAINT FK_7656F53B6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE trips ADD CONSTRAINT FK_7656F53BDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE user_trip ADD CONSTRAINT FK_CD7B9F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_trip ADD CONSTRAINT FK_CD7B9F2A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trips (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD8BAC62AF');
        $this->addSql('ALTER TABLE trips DROP FOREIGN KEY FK_7656F53B61220EA6');
        $this->addSql('ALTER TABLE trips DROP FOREIGN KEY FK_7656F53BAF5D55E1');
        $this->addSql('ALTER TABLE trips DROP FOREIGN KEY FK_7656F53B6BF700BD');
        $this->addSql('ALTER TABLE trips DROP FOREIGN KEY FK_7656F53BDA6A219');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF5D55E1');
        $this->addSql('ALTER TABLE user_trip DROP FOREIGN KEY FK_CD7B9F2A76ED395');
        $this->addSql('ALTER TABLE user_trip DROP FOREIGN KEY FK_CD7B9F2A5BC2E0E');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE trips');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_trip');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
