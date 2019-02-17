<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190217112445 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accessibility_check_result_related_node ADD accessibility_check_result_id INT NOT NULL');
        $this->addSql('ALTER TABLE accessibility_check_result_related_node ADD CONSTRAINT FK_A52C74FCB8B831EE FOREIGN KEY (accessibility_check_result_id) REFERENCES accessibility_check_result (id)');
        $this->addSql('CREATE INDEX IDX_A52C74FCB8B831EE ON accessibility_check_result_related_node (accessibility_check_result_id)');
        $this->addSql('ALTER TABLE scan CHANGE scan_type scan_type JSON NOT NULL');
        $this->addSql('ALTER TABLE scan_url CHANGE content_type content_type VARCHAR(255) DEFAULT NULL, CHANGE byte_size byte_size INT DEFAULT NULL, CHANGE http_status http_status INT DEFAULT NULL, CHANGE scan_status scan_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accessibility_check_result_related_node DROP FOREIGN KEY FK_A52C74FCB8B831EE');
        $this->addSql('DROP INDEX IDX_A52C74FCB8B831EE ON accessibility_check_result_related_node');
        $this->addSql('ALTER TABLE accessibility_check_result_related_node DROP accessibility_check_result_id');
        $this->addSql('ALTER TABLE scan CHANGE scan_type scan_type LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE scan_url CHANGE content_type content_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE byte_size byte_size INT DEFAULT NULL, CHANGE http_status http_status INT DEFAULT NULL, CHANGE scan_status scan_status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
