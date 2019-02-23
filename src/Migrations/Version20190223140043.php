<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190223140043 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE axe_check (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, impact VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_check_result (id INT AUTO_INCREMENT NOT NULL, based_on_check_id INT NOT NULL, data JSON DEFAULT NULL, message LONGTEXT DEFAULT NULL, related_nodes JSON DEFAULT NULL, INDEX IDX_898CBCBBD2BD8C52 (based_on_check_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_result (id INT AUTO_INCREMENT NOT NULL, result_type_id INT NOT NULL, html LONGTEXT DEFAULT NULL, target JSON DEFAULT NULL, summary LONGTEXT DEFAULT NULL, INDEX IDX_79EA31C4A7750E77 (result_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_result_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_rule (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, impact VARCHAR(255) DEFAULT NULL, description VARCHAR(1024) DEFAULT NULL, help VARCHAR(1024) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_rules_tags (rule_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_1466E34D744E0351 (rule_id), INDEX IDX_1466E34DBAD26311 (tag_id), PRIMARY KEY(rule_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_rules_checks (rule_id INT NOT NULL, check_id INT NOT NULL, INDEX IDX_D7E42D50744E0351 (rule_id), INDEX IDX_D7E42D50709385E7 (check_id), PRIMARY KEY(rule_id, check_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, root_url VARCHAR(2048) NOT NULL, date_time_created DATETIME NOT NULL, name VARCHAR(1024) NOT NULL, INDEX IDX_8BF21CDE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, scan_type JSON NOT NULL, INDEX IDX_C4B3B3AE549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scanner (id INT AUTO_INCREMENT NOT NULL, scanner_type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan_url (id INT AUTO_INCREMENT NOT NULL, scan_id INT NOT NULL, url VARCHAR(2048) NOT NULL, content_type VARCHAR(255) DEFAULT NULL, byte_size INT DEFAULT NULL, http_status INT DEFAULT NULL, scan_status VARCHAR(255) DEFAULT NULL, INDEX IDX_805089F92827AAD3 (scan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_time_created DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_client (user_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_A2161F68A76ED395 (user_id), INDEX IDX_A2161F6819EB6921 (client_id), PRIMARY KEY(user_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE axe_check_result ADD CONSTRAINT FK_898CBCBBD2BD8C52 FOREIGN KEY (based_on_check_id) REFERENCES axe_check (id)');
        $this->addSql('ALTER TABLE axe_result ADD CONSTRAINT FK_79EA31C4A7750E77 FOREIGN KEY (result_type_id) REFERENCES axe_result_type (id)');
        $this->addSql('ALTER TABLE axe_rules_tags ADD CONSTRAINT FK_1466E34D744E0351 FOREIGN KEY (rule_id) REFERENCES axe_rule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE axe_rules_tags ADD CONSTRAINT FK_1466E34DBAD26311 FOREIGN KEY (tag_id) REFERENCES axe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE axe_rules_checks ADD CONSTRAINT FK_D7E42D50744E0351 FOREIGN KEY (rule_id) REFERENCES axe_rule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE axe_rules_checks ADD CONSTRAINT FK_D7E42D50709385E7 FOREIGN KEY (check_id) REFERENCES axe_check (id) ON DELETE CASCADE');
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

        $this->addSql('ALTER TABLE axe_check_result DROP FOREIGN KEY FK_898CBCBBD2BD8C52');
        $this->addSql('ALTER TABLE axe_rules_checks DROP FOREIGN KEY FK_D7E42D50709385E7');
        $this->addSql('ALTER TABLE axe_result DROP FOREIGN KEY FK_79EA31C4A7750E77');
        $this->addSql('ALTER TABLE axe_rules_tags DROP FOREIGN KEY FK_1466E34D744E0351');
        $this->addSql('ALTER TABLE axe_rules_checks DROP FOREIGN KEY FK_D7E42D50744E0351');
        $this->addSql('ALTER TABLE axe_rules_tags DROP FOREIGN KEY FK_1466E34DBAD26311');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE19EB6921');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F6819EB6921');
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AE549213EC');
        $this->addSql('ALTER TABLE scan_url DROP FOREIGN KEY FK_805089F92827AAD3');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F68A76ED395');
        $this->addSql('DROP TABLE axe_check');
        $this->addSql('DROP TABLE axe_check_result');
        $this->addSql('DROP TABLE axe_result');
        $this->addSql('DROP TABLE axe_result_type');
        $this->addSql('DROP TABLE axe_rule');
        $this->addSql('DROP TABLE axe_rules_tags');
        $this->addSql('DROP TABLE axe_rules_checks');
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
