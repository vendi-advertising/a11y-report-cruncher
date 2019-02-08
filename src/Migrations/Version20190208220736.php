<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190208220736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan CHANGE date_time_created date_time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scanner_type CHANGE date_time_created date_time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE date_time_created date_time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE date_time_created date_time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scanner CHANGE date_time_created date_time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE scan_batch CHANGE date_time_created date_time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE date_time_created date_time_created DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property_scan CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scan_batch CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE scanner CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE scanner_type CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}
