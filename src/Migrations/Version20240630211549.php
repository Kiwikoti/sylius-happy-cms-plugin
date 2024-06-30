<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630211549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_happy_cms__page ADD parent_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_happy_cms__page ADD CONSTRAINT FK_B39EF098727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_happy_cms__page (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_B39EF098727ACA70 ON sylius_happy_cms__page (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_happy_cms__page DROP FOREIGN KEY FK_B39EF098727ACA70');
        $this->addSql('DROP INDEX IDX_B39EF098727ACA70 ON sylius_happy_cms__page');
        $this->addSql('ALTER TABLE sylius_happy_cms__page DROP parent_id');
    }
}
