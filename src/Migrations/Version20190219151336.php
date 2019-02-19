<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190219151336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE accessibility_check (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accessibility_check_result (id INT AUTO_INCREMENT NOT NULL, accessibility_check_version_id INT NOT NULL, scan_url_id INT NOT NULL, category VARCHAR(255) DEFAULT NULL, INDEX IDX_8EAAF3A8E364E7D7 (accessibility_check_version_id), INDEX IDX_8EAAF3A85D7EFF19 (scan_url_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accessibility_check_result_related_node (id INT AUTO_INCREMENT NOT NULL, accessibility_check_result_id INT NOT NULL, html LONGTEXT NOT NULL, targets LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_A52C74FCB8B831EE (accessibility_check_result_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accessibility_check_version (id INT AUTO_INCREMENT NOT NULL, accessibility_check_id INT NOT NULL, message VARCHAR(255) NOT NULL, impact VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, INDEX IDX_E33279F5D5CA9116 (accessibility_check_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accessibility_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, root_url VARCHAR(2048) NOT NULL, date_time_created DATETIME NOT NULL, name VARCHAR(1024) NOT NULL, INDEX IDX_8BF21CDE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, scan_type JSON NOT NULL, INDEX IDX_C4B3B3AE549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scanner (id INT AUTO_INCREMENT NOT NULL, scanner_type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan_url (id INT AUTO_INCREMENT NOT NULL, scan_id INT NOT NULL, url VARCHAR(2048) NOT NULL, content_type VARCHAR(255) DEFAULT NULL, byte_size INT DEFAULT NULL, http_status INT DEFAULT NULL, scan_status VARCHAR(255) DEFAULT NULL, INDEX IDX_805089F92827AAD3 (scan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_client (user_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_A2161F68A76ED395 (user_id), INDEX IDX_A2161F6819EB6921 (client_id), PRIMARY KEY(user_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accessibility_check_result ADD CONSTRAINT FK_8EAAF3A8E364E7D7 FOREIGN KEY (accessibility_check_version_id) REFERENCES accessibility_check_version (id)');
        $this->addSql('ALTER TABLE accessibility_check_result ADD CONSTRAINT FK_8EAAF3A85D7EFF19 FOREIGN KEY (scan_url_id) REFERENCES scan_url (id)');
        $this->addSql('ALTER TABLE accessibility_check_result_related_node ADD CONSTRAINT FK_A52C74FCB8B831EE FOREIGN KEY (accessibility_check_result_id) REFERENCES accessibility_check_result (id)');
        $this->addSql('ALTER TABLE accessibility_check_version ADD CONSTRAINT FK_E33279F5D5CA9116 FOREIGN KEY (accessibility_check_id) REFERENCES accessibility_check (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE scan ADD CONSTRAINT FK_C4B3B3AE549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE scan_url ADD CONSTRAINT FK_805089F92827AAD3 FOREIGN KEY (scan_id) REFERENCES scan (id)');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F68A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F6819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accessibility_check_version DROP FOREIGN KEY FK_E33279F5D5CA9116');
        $this->addSql('ALTER TABLE accessibility_check_result_related_node DROP FOREIGN KEY FK_A52C74FCB8B831EE');
        $this->addSql('ALTER TABLE accessibility_check_result DROP FOREIGN KEY FK_8EAAF3A8E364E7D7');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE19EB6921');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F6819EB6921');
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AE549213EC');
        $this->addSql('ALTER TABLE scan_url DROP FOREIGN KEY FK_805089F92827AAD3');
        $this->addSql('ALTER TABLE accessibility_check_result DROP FOREIGN KEY FK_8EAAF3A85D7EFF19');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F68A76ED395');
        $this->addSql('DROP TABLE accessibility_check');
        $this->addSql('DROP TABLE accessibility_check_result');
        $this->addSql('DROP TABLE accessibility_check_result_related_node');
        $this->addSql('DROP TABLE accessibility_check_version');
        $this->addSql('DROP TABLE accessibility_tag');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE scan');
        $this->addSql('DROP TABLE scanner');
        $this->addSql('DROP TABLE scan_url');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_client');
    }
}
