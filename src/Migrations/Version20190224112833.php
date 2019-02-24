<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190224112833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE axe_result DROP FOREIGN KEY FK_79EA31C4A7750E77');
        $this->addSql('DROP TABLE axe_result_type');
        $this->addSql('ALTER TABLE axe_check CHANGE impact impact VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE axe_check_result CHANGE data data JSON DEFAULT NULL, CHANGE related_nodes related_nodes JSON DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_79EA31C4A7750E77 ON axe_result');
        $this->addSql('ALTER TABLE axe_result ADD result_type LONGTEXT DEFAULT NULL, DROP result_type_id, CHANGE target target JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE axe_rule CHANGE impact impact VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(1024) DEFAULT NULL, CHANGE help help VARCHAR(1024) DEFAULT NULL');
        $this->addSql('ALTER TABLE scan CHANGE scan_type scan_type JSON NOT NULL');
        $this->addSql('ALTER TABLE scan_url CHANGE content_type content_type VARCHAR(255) DEFAULT NULL, CHANGE byte_size byte_size INT DEFAULT NULL, CHANGE http_status http_status INT DEFAULT NULL, CHANGE scan_status scan_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE axe_result_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE axe_check CHANGE impact impact VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE type type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE axe_check_result CHANGE data data LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE related_nodes related_nodes LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE axe_result ADD result_type_id INT NOT NULL, DROP result_type, CHANGE target target LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE axe_result ADD CONSTRAINT FK_79EA31C4A7750E77 FOREIGN KEY (result_type_id) REFERENCES axe_result_type (id)');
        $this->addSql('CREATE INDEX IDX_79EA31C4A7750E77 ON axe_result (result_type_id)');
        $this->addSql('ALTER TABLE axe_rule CHANGE impact impact VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE description description VARCHAR(1024) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE help help VARCHAR(1024) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scan CHANGE scan_type scan_type LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE scan_url CHANGE content_type content_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE byte_size byte_size INT DEFAULT NULL, CHANGE http_status http_status INT DEFAULT NULL, CHANGE scan_status scan_status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
