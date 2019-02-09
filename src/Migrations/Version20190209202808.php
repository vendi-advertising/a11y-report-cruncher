<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190209202808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scan_batch DROP FOREIGN KEY FK_9F2363FA3A117388');
        $this->addSql('DROP INDEX IDX_9F2363FA3A117388 ON scan_batch');
        $this->addSql('ALTER TABLE scan_batch CHANGE scanner_id_id scanner_id INT NOT NULL');
        $this->addSql('ALTER TABLE scan_batch ADD CONSTRAINT FK_9F2363FA67C89E33 FOREIGN KEY (scanner_id) REFERENCES scanner (id)');
        $this->addSql('CREATE INDEX IDX_9F2363FA67C89E33 ON scan_batch (scanner_id)');
        $this->addSql('ALTER TABLE scan_batch_url DROP FOREIGN KEY FK_C44E265A87EB27DF');
        $this->addSql('ALTER TABLE scan_batch_url DROP FOREIGN KEY FK_C44E265ADB2FBA34');
        $this->addSql('DROP INDEX IDX_C44E265A87EB27DF ON scan_batch_url');
        $this->addSql('DROP INDEX IDX_C44E265ADB2FBA34 ON scan_batch_url');
        $this->addSql('ALTER TABLE scan_batch_url ADD scan_batch_id INT NOT NULL, ADD property_scan_url_id INT NOT NULL, DROP scan_batch_id_id, DROP property_scan_url_id_id');
        $this->addSql('ALTER TABLE scan_batch_url ADD CONSTRAINT FK_C44E265A32A57ABF FOREIGN KEY (scan_batch_id) REFERENCES scan_batch (id)');
        $this->addSql('ALTER TABLE scan_batch_url ADD CONSTRAINT FK_C44E265AE21B3F2F FOREIGN KEY (property_scan_url_id) REFERENCES property_scan_url (id)');
        $this->addSql('CREATE INDEX IDX_C44E265A32A57ABF ON scan_batch_url (scan_batch_id)');
        $this->addSql('CREATE INDEX IDX_C44E265AE21B3F2F ON scan_batch_url (property_scan_url_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD826303A117388');
        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD8263087EB27DF');
        $this->addSql('DROP INDEX IDX_4FD826303A117388 ON property_scan_url_log');
        $this->addSql('DROP INDEX IDX_4FD8263087EB27DF ON property_scan_url_log');
        $this->addSql('ALTER TABLE property_scan_url_log ADD property_scan_url_id INT NOT NULL, ADD scanner_id INT NOT NULL, DROP property_scan_url_id_id, DROP scanner_id_id, CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD82630E21B3F2F FOREIGN KEY (property_scan_url_id) REFERENCES property_scan_url (id)');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD8263067C89E33 FOREIGN KEY (scanner_id) REFERENCES scanner (id)');
        $this->addSql('CREATE INDEX IDX_4FD82630E21B3F2F ON property_scan_url_log (property_scan_url_id)');
        $this->addSql('CREATE INDEX IDX_4FD8263067C89E33 ON property_scan_url_log (scanner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD82630E21B3F2F');
        $this->addSql('ALTER TABLE property_scan_url_log DROP FOREIGN KEY FK_4FD8263067C89E33');
        $this->addSql('DROP INDEX IDX_4FD82630E21B3F2F ON property_scan_url_log');
        $this->addSql('DROP INDEX IDX_4FD8263067C89E33 ON property_scan_url_log');
        $this->addSql('ALTER TABLE property_scan_url_log ADD property_scan_url_id_id INT NOT NULL, ADD scanner_id_id INT NOT NULL, DROP property_scan_url_id, DROP scanner_id, CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD826303A117388 FOREIGN KEY (scanner_id_id) REFERENCES scanner (id)');
        $this->addSql('ALTER TABLE property_scan_url_log ADD CONSTRAINT FK_4FD8263087EB27DF FOREIGN KEY (property_scan_url_id_id) REFERENCES property_scan_url (id)');
        $this->addSql('CREATE INDEX IDX_4FD826303A117388 ON property_scan_url_log (scanner_id_id)');
        $this->addSql('CREATE INDEX IDX_4FD8263087EB27DF ON property_scan_url_log (property_scan_url_id_id)');
        $this->addSql('ALTER TABLE scan_batch DROP FOREIGN KEY FK_9F2363FA67C89E33');
        $this->addSql('DROP INDEX IDX_9F2363FA67C89E33 ON scan_batch');
        $this->addSql('ALTER TABLE scan_batch CHANGE scanner_id scanner_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE scan_batch ADD CONSTRAINT FK_9F2363FA3A117388 FOREIGN KEY (scanner_id_id) REFERENCES scanner (id)');
        $this->addSql('CREATE INDEX IDX_9F2363FA3A117388 ON scan_batch (scanner_id_id)');
        $this->addSql('ALTER TABLE scan_batch_url DROP FOREIGN KEY FK_C44E265A32A57ABF');
        $this->addSql('ALTER TABLE scan_batch_url DROP FOREIGN KEY FK_C44E265AE21B3F2F');
        $this->addSql('DROP INDEX IDX_C44E265A32A57ABF ON scan_batch_url');
        $this->addSql('DROP INDEX IDX_C44E265AE21B3F2F ON scan_batch_url');
        $this->addSql('ALTER TABLE scan_batch_url ADD scan_batch_id_id INT NOT NULL, ADD property_scan_url_id_id INT NOT NULL, DROP scan_batch_id, DROP property_scan_url_id');
        $this->addSql('ALTER TABLE scan_batch_url ADD CONSTRAINT FK_C44E265A87EB27DF FOREIGN KEY (property_scan_url_id_id) REFERENCES property_scan_url (id)');
        $this->addSql('ALTER TABLE scan_batch_url ADD CONSTRAINT FK_C44E265ADB2FBA34 FOREIGN KEY (scan_batch_id_id) REFERENCES scan_batch (id)');
        $this->addSql('CREATE INDEX IDX_C44E265A87EB27DF ON scan_batch_url (property_scan_url_id_id)');
        $this->addSql('CREATE INDEX IDX_C44E265ADB2FBA34 ON scan_batch_url (scan_batch_id_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
