<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190224132148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE axe_result_node (id INT AUTO_INCREMENT NOT NULL, rule_result_base_id INT NOT NULL, impact VARCHAR(255) DEFAULT NULL, html LONGTEXT DEFAULT NULL, target JSON DEFAULT NULL, failure_summary LONGTEXT DEFAULT NULL, INDEX IDX_D6D0DE485EF1F704 (rule_result_base_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_result_node_detail (id INT AUTO_INCREMENT NOT NULL, rule_result_node_id INT NOT NULL, name VARCHAR(255) NOT NULL, data JSON DEFAULT NULL, related_nodes JSON DEFAULT NULL, impact VARCHAR(255) DEFAULT NULL, message VARCHAR(1024) DEFAULT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_22F474D719BB792 (rule_result_node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_result (id INT AUTO_INCREMENT NOT NULL, scan_result_id INT NOT NULL, impact VARCHAR(255) DEFAULT NULL, description VARCHAR(1024) DEFAULT NULL, help VARCHAR(1024) DEFAULT NULL, name VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_79EA31C4EC68BBB8 (scan_result_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_results_tags (rule_result_base_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3C15F2FB5EF1F704 (rule_result_base_id), INDEX IDX_3C15F2FBBAD26311 (tag_id), PRIMARY KEY(rule_result_base_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan_result (id INT AUTO_INCREMENT NOT NULL, scan_url_id INT NOT NULL, UNIQUE INDEX UNIQ_CFDBE4ED5D7EFF19 (scan_url_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, root_url VARCHAR(2048) NOT NULL, date_time_created DATETIME NOT NULL, name VARCHAR(1024) NOT NULL, INDEX IDX_8BF21CDE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, scan_type JSON NOT NULL, INDEX IDX_C4B3B3AE549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scanner (id INT AUTO_INCREMENT NOT NULL, scanner_type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan_url (id INT AUTO_INCREMENT NOT NULL, scan_id INT NOT NULL, url VARCHAR(2048) NOT NULL, content_type VARCHAR(255) DEFAULT NULL, byte_size INT DEFAULT NULL, http_status INT DEFAULT NULL, scan_status VARCHAR(255) DEFAULT NULL, INDEX IDX_805089F92827AAD3 (scan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_client (user_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_A2161F68A76ED395 (user_id), INDEX IDX_A2161F6819EB6921 (client_id), PRIMARY KEY(user_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE axe_result_node ADD CONSTRAINT FK_D6D0DE485EF1F704 FOREIGN KEY (rule_result_base_id) REFERENCES axe_result (id)');
        $this->addSql('ALTER TABLE axe_result_node_detail ADD CONSTRAINT FK_22F474D719BB792 FOREIGN KEY (rule_result_node_id) REFERENCES axe_result_node (id)');
        $this->addSql('ALTER TABLE axe_result ADD CONSTRAINT FK_79EA31C4EC68BBB8 FOREIGN KEY (scan_result_id) REFERENCES scan_result (id)');
        $this->addSql('ALTER TABLE axe_results_tags ADD CONSTRAINT FK_3C15F2FB5EF1F704 FOREIGN KEY (rule_result_base_id) REFERENCES axe_result (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE axe_results_tags ADD CONSTRAINT FK_3C15F2FBBAD26311 FOREIGN KEY (tag_id) REFERENCES axe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scan_result ADD CONSTRAINT FK_CFDBE4ED5D7EFF19 FOREIGN KEY (scan_url_id) REFERENCES scan_url (id)');
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

        $this->addSql('ALTER TABLE axe_result_node_detail DROP FOREIGN KEY FK_22F474D719BB792');
        $this->addSql('ALTER TABLE axe_result_node DROP FOREIGN KEY FK_D6D0DE485EF1F704');
        $this->addSql('ALTER TABLE axe_results_tags DROP FOREIGN KEY FK_3C15F2FB5EF1F704');
        $this->addSql('ALTER TABLE axe_result DROP FOREIGN KEY FK_79EA31C4EC68BBB8');
        $this->addSql('ALTER TABLE axe_results_tags DROP FOREIGN KEY FK_3C15F2FBBAD26311');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE19EB6921');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F6819EB6921');
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AE549213EC');
        $this->addSql('ALTER TABLE scan_url DROP FOREIGN KEY FK_805089F92827AAD3');
        $this->addSql('ALTER TABLE scan_result DROP FOREIGN KEY FK_CFDBE4ED5D7EFF19');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F68A76ED395');
        $this->addSql('DROP TABLE axe_result_node');
        $this->addSql('DROP TABLE axe_result_node_detail');
        $this->addSql('DROP TABLE axe_result');
        $this->addSql('DROP TABLE axe_results_tags');
        $this->addSql('DROP TABLE scan_result');
        $this->addSql('DROP TABLE axe_tag');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE scan');
        $this->addSql('DROP TABLE scanner');
        $this->addSql('DROP TABLE scan_url');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_client');
    }
}
