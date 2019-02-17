<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190217171209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accessibility_check_result ADD scan_url_id INT NOT NULL');
        $this->addSql('ALTER TABLE accessibility_check_result ADD CONSTRAINT FK_8EAAF3A85D7EFF19 FOREIGN KEY (scan_url_id) REFERENCES scan_url (id)');
        $this->addSql('CREATE INDEX IDX_8EAAF3A85D7EFF19 ON accessibility_check_result (scan_url_id)');
        $this->addSql('ALTER TABLE scan CHANGE scan_type scan_type JSON NOT NULL');
        $this->addSql('ALTER TABLE scan_url CHANGE content_type content_type VARCHAR(255) DEFAULT NULL, CHANGE byte_size byte_size INT DEFAULT NULL, CHANGE http_status http_status INT DEFAULT NULL, CHANGE scan_status scan_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accessibility_check_result DROP FOREIGN KEY FK_8EAAF3A85D7EFF19');
        $this->addSql('DROP INDEX IDX_8EAAF3A85D7EFF19 ON accessibility_check_result');
        $this->addSql('ALTER TABLE accessibility_check_result DROP scan_url_id');
        $this->addSql('ALTER TABLE scan CHANGE scan_type scan_type LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE scan_url CHANGE content_type content_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE byte_size byte_size INT DEFAULT NULL, CHANGE http_status http_status INT DEFAULT NULL, CHANGE scan_status scan_status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
