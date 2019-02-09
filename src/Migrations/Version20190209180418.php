<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190209180418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan DROP FOREIGN KEY FK_DBAB0C94B9575F5A');
        $this->addSql('DROP INDEX IDX_DBAB0C94B9575F5A ON property_scan');
        $this->addSql('ALTER TABLE property_scan CHANGE property_id_id property_id INT NOT NULL');
        $this->addSql('ALTER TABLE property_scan ADD CONSTRAINT FK_DBAB0C94549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('CREATE INDEX IDX_DBAB0C94549213EC ON property_scan (property_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE status status VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_scan DROP FOREIGN KEY FK_DBAB0C94549213EC');
        $this->addSql('DROP INDEX IDX_DBAB0C94549213EC ON property_scan');
        $this->addSql('ALTER TABLE property_scan CHANGE property_id property_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE property_scan ADD CONSTRAINT FK_DBAB0C94B9575F5A FOREIGN KEY (property_id_id) REFERENCES property (id)');
        $this->addSql('CREATE INDEX IDX_DBAB0C94B9575F5A ON property_scan (property_id_id)');
        $this->addSql('ALTER TABLE property_scan_url_log CHANGE status status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
