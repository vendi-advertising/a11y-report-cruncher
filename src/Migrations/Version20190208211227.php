<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190208211227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE scanner_type CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE scanner CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE scan_batch DROP FOREIGN KEY FK_9F2363FA87EB27DF');
        $this->addSql('DROP INDEX IDX_9F2363FA87EB27DF ON scan_batch');
        $this->addSql('ALTER TABLE scan_batch DROP property_scan_url_id_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property_scan CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scan_batch ADD property_scan_url_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE scan_batch ADD CONSTRAINT FK_9F2363FA87EB27DF FOREIGN KEY (property_scan_url_id_id) REFERENCES property_scan_url (id)');
        $this->addSql('CREATE INDEX IDX_9F2363FA87EB27DF ON scan_batch (property_scan_url_id_id)');
        $this->addSql('ALTER TABLE scanner CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE scanner_type CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}
