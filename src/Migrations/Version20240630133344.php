<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630133344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_happy_cms__folder (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, INDEX IDX_5F016E4C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__media (id INT AUTO_INCREMENT NOT NULL, folder_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, mime VARCHAR(255) DEFAULT NULL, size INT DEFAULT NULL, lastModified INT DEFAULT NULL, metas JSON NOT NULL, INDEX IDX_AF310EF4162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_happy_cms__folder ADD CONSTRAINT FK_5F016E4C727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_happy_cms__folder (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_happy_cms__media ADD CONSTRAINT FK_AF310EF4162CB942 FOREIGN KEY (folder_id) REFERENCES sylius_happy_cms__folder (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_happy_cms__folder DROP FOREIGN KEY FK_5F016E4C727ACA70');
        $this->addSql('ALTER TABLE sylius_happy_cms__media DROP FOREIGN KEY FK_AF310EF4162CB942');
        $this->addSql('DROP TABLE sylius_happy_cms__folder');
        $this->addSql('DROP TABLE sylius_happy_cms__media');
    }
}
