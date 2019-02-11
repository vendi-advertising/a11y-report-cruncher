<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211222617 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE property_scan_url_log (id INT AUTO_INCREMENT NOT NULL, property_scan_url_id INT NOT NULL, scanner_id INT NOT NULL, entry_direction VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, info LONGTEXT DEFAULT NULL, INDEX IDX_4FD82630E21B3F2F (property_scan_url_id), INDEX IDX_4FD8263067C89E33 (scanner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scanner (id INT AUTO_INCREMENT NOT NULL, scanner_type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_scan_url (id INT AUTO_INCREMENT NOT NULL, property_scan_id INT NOT NULL, url VARCHAR(2048) NOT NULL, INDEX IDX_DED7BDBD71EAE0AB (property_scan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, root_url VARCHAR(2048) NOT NULL, date_time_created DATETIME NOT NULL, name VARCHAR(1024) NOT NULL, INDEX IDX_8BF21CDE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_scan (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, date_time_created DATETIME NOT NULL, INDEX IDX_DBAB0C94549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_client (user_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_A2161F68A76ED395 (user_id), INDEX IDX_A2161F6819EB6921 (client_id), PRIMARY KEY(user_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD82630E21B3F2F FOREIGN KEY (property_scan_url_id) REFERENCES property_scan_url (id)');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD8263067C89E33 FOREIGN KEY (scanner_id) REFERENCES scanner (id)');
        $this->addSql('ALTER TABLE property_scan_url ADD CONSTRAINT FK_DED7BDBD71EAE0AB FOREIGN KEY (property_scan_id) REFERENCES property_scan (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE property_scan ADD CONSTRAINT FK_DBAB0C94549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F68A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F6819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD8263067C89E33');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE19EB6921');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F6819EB6921');
        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD82630E21B3F2F');
        $this->addSql('ALTER TABLE property_scan DROP FOREIGN KEY FK_DBAB0C94549213EC');
        $this->addSql('ALTER TABLE property_scan_url DROP FOREIGN KEY FK_DED7BDBD71EAE0AB');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F68A76ED395');
        $this->addSql('DROP TABLE property_scan_url_log');
        $this->addSql('DROP TABLE scanner');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE property_scan_url');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_scan');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_client');
    }
}
