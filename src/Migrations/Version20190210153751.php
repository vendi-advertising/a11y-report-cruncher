<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210153751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scanner DROP FOREIGN KEY FK_55EFDF291D578ED3');
        $this->addSql('DROP TABLE scanner_type');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE entry_type entry_type VARCHAR(255) DEFAULT NULL, CHANGE entry_direction entry_direction VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_55EFDF291D578ED3 ON scanner');
        $this->addSql('ALTER TABLE scanner ADD scanner_type VARCHAR(255) NOT NULL, DROP scanner_type_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scanner_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, date_time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE entry_type entry_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE entry_direction entry_direction VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scanner ADD scanner_type_id INT NOT NULL, DROP scanner_type');
        $this->addSql('ALTER TABLE scanner ADD CONSTRAINT FK_55EFDF291D578ED3 FOREIGN KEY (scanner_type_id) REFERENCES scanner_type (id)');
        $this->addSql('CREATE INDEX IDX_55EFDF291D578ED3 ON scanner (scanner_type_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
