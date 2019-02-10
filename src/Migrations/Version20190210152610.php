<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210152610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD826304D03EC78');
        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD82630E9AA2304');
        $this->addSql('DROP TABLE url_log_entry_direction');
        $this->addSql('DROP TABLE url_log_entry_type');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('DROP INDEX IDX_4FD826304D03EC78 ON property_scan_url_log');
        $this->addSql('DROP INDEX IDX_4FD82630E9AA2304 ON property_scan_url_log');
        $this->addSql('ALTER TABLE property_scan_url_log ADD entry_type VARCHAR(255) DEFAULT NULL, ADD entry_direction VARCHAR(255) DEFAULT NULL, DROP entry_type_id, DROP entry_direction_id, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE url_log_entry_direction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE url_log_entry_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE property_scan_url_log ADD entry_type_id INT NOT NULL, ADD entry_direction_id INT NOT NULL, DROP entry_type, DROP entry_direction, CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD826304D03EC78 FOREIGN KEY (entry_direction_id) REFERENCES url_log_entry_direction (id)');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD82630E9AA2304 FOREIGN KEY (entry_type_id) REFERENCES url_log_entry_type (id)');
        $this->addSql('CREATE INDEX IDX_4FD826304D03EC78 ON property_scan_url_log (entry_direction_id)');
        $this->addSql('CREATE INDEX IDX_4FD82630E9AA2304 ON property_scan_url_log (entry_type_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
