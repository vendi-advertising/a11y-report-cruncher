<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210190451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scan_batch_url ADD property_scan_url_log_id INT NOT NULL');
        $this->addSql('ALTER TABLE scan_batch_url ADD CONSTRAINT FK_C44E265A9FCF92D FOREIGN KEY (property_scan_url_log_id) REFERENCES property_scan_url_log (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C44E265A9FCF92D ON scan_batch_url (property_scan_url_log_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE entry_direction entry_direction VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan_url_log CHANGE entry_direction entry_direction VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scan_batch_url DROP FOREIGN KEY FK_C44E265A9FCF92D');
        $this->addSql('DROP INDEX UNIQ_C44E265A9FCF92D ON scan_batch_url');
        $this->addSql('ALTER TABLE scan_batch_url DROP property_scan_url_log_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
