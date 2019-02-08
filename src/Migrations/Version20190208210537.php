<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190208210537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE url_log_entry_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE url_log_entry_direction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_scan_url_log (id INT AUTO_INCREMENT NOT NULL, property_scan_url_id_id INT NOT NULL, scanner_id_id INT NOT NULL, entry_type_id INT NOT NULL, entry_direction_id INT NOT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_4FD8263087EB27DF (property_scan_url_id_id), INDEX IDX_4FD826303A117388 (scanner_id_id), INDEX IDX_4FD82630E9AA2304 (entry_type_id), INDEX IDX_4FD826304D03EC78 (entry_direction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_scan_url (id INT AUTO_INCREMENT NOT NULL, property_scan_id_id INT NOT NULL, url VARCHAR(2048) NOT NULL, INDEX IDX_DED7BDBDADCD8F4A (property_scan_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD8263087EB27DF FOREIGN KEY (property_scan_url_id_id) REFERENCES property_scan_url (id)');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD826303A117388 FOREIGN KEY (scanner_id_id) REFERENCES scanner (id)');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD82630E9AA2304 FOREIGN KEY (entry_type_id) REFERENCES url_log_entry_type (id)');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD826304D03EC78 FOREIGN KEY (entry_direction_id) REFERENCES url_log_entry_direction (id)');
        $this->addSql('ALTER TABLE property_scan_url ADD CONSTRAINT FK_DED7BDBDADCD8F4A FOREIGN KEY (property_scan_id_id) REFERENCES property_scan (id)');
        $this->addSql('ALTER TABLE property_scan CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE scanner_type CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE scanner CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE date_time_created date_time_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD82630E9AA2304');
        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD826304D03EC78');
        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD8263087EB27DF');
        $this->addSql('DROP TABLE url_log_entry_type');
        $this->addSql('DROP TABLE url_log_entry_direction');
        $this->addSql('DROP TABLE property_scan_url_log');
        $this->addSql('DROP TABLE property_scan_url');
        $this->addSql('ALTER TABLE client CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE property_scan CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE scanner CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE scanner_type CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE date_time_created date_time_created DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}
