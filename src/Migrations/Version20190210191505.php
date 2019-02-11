<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210191505 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scan_batch_url DROP FOREIGN KEY FK_C44E265A32A57ABF');
        $this->addSql('DROP TABLE scan_batch');
        $this->addSql('DROP TABLE scan_batch_url');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE entry_direction entry_direction VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scan_batch (id INT AUTO_INCREMENT NOT NULL, scanner_id INT NOT NULL, date_time_expires DATETIME NOT NULL, date_time_created DATETIME NOT NULL, INDEX IDX_9F2363FA67C89E33 (scanner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE scan_batch_url (id INT AUTO_INCREMENT NOT NULL, scan_batch_id INT NOT NULL, property_scan_url_log_id INT NOT NULL, INDEX IDX_C44E265A32A57ABF (scan_batch_id), UNIQUE INDEX UNIQ_C44E265A9FCF92D (property_scan_url_log_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE scan_batch ADD CONSTRAINT FK_9F2363FA67C89E33 FOREIGN KEY (scanner_id) REFERENCES scanner (id)');
        $this->addSql('ALTER TABLE scan_batch_url ADD CONSTRAINT FK_C44E265A32A57ABF FOREIGN KEY (scan_batch_id) REFERENCES scan_batch (id)');
        $this->addSql('ALTER TABLE scan_batch_url ADD CONSTRAINT FK_C44E265A9FCF92D FOREIGN KEY (property_scan_url_log_id) REFERENCES property_scan_url_log (id)');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE entry_direction entry_direction VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
