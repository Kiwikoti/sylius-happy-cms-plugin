<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630204739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_happy_cms__config (id INT UNSIGNED AUTO_INCREMENT NOT NULL, config VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_672948FDD48A2F7C (config), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__config_translation (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED NOT NULL, value LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_D73724B62C2AC5D3 (translatable_id), UNIQUE INDEX sylius_happy_cms__config_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__menu (id INT UNSIGNED AUTO_INCREMENT NOT NULL, code VARCHAR(30) NOT NULL, name VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__menu_item (id INT UNSIGNED AUTO_INCREMENT NOT NULL, menu_id INT UNSIGNED DEFAULT NULL, parent_id INT UNSIGNED DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, root INT DEFAULT NULL, class_attribute VARCHAR(255) DEFAULT NULL, position SMALLINT UNSIGNED DEFAULT NULL, target TINYINT(1) DEFAULT 0, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, publishState VARCHAR(100) NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, INDEX IDX_52C0AB66CCD7E912 (menu_id), INDEX IDX_52C0AB66727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__menu_item_translation (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_DBFC64242C2AC5D3 (translatable_id), UNIQUE INDEX sylius_happy_cms__menu_item_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__page (id INT UNSIGNED AUTO_INCREMENT NOT NULL, action VARCHAR(255) DEFAULT NULL, template VARCHAR(255) DEFAULT NULL, css LONGTEXT DEFAULT NULL, js LONGTEXT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, publishState VARCHAR(100) NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE happy_cms_page__page_route (page_id INT UNSIGNED NOT NULL, route_id INT NOT NULL, INDEX IDX_875B8BC5C4663E4 (page_id), INDEX IDX_875B8BC534ECB4E6 (route_id), PRIMARY KEY(page_id, route_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED NOT NULL, content JSON DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, seo_title VARCHAR(255) NOT NULL, seo_description LONGTEXT DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_canonical VARCHAR(255) DEFAULT NULL, seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:happy_cms_media_type)\', seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap TINYINT(1) NOT NULL, seo_robots JSON NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_A74F201D2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_happy_cms__page_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__shared_block (id INT UNSIGNED AUTO_INCREMENT NOT NULL, block_key VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, settings JSON NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, name VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1B4DE0BDE81B6293 (block_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_happy_cms__shared_block_translation (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_62715C802C2AC5D3 (translatable_id), UNIQUE INDEX sylius_happy_cms__shared_block_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_happy_cms__config_translation ADD CONSTRAINT FK_D73724B62C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_happy_cms__config (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_happy_cms__menu_item ADD CONSTRAINT FK_52C0AB66CCD7E912 FOREIGN KEY (menu_id) REFERENCES sylius_happy_cms__menu (id)');
        $this->addSql('ALTER TABLE sylius_happy_cms__menu_item ADD CONSTRAINT FK_52C0AB66727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_happy_cms__menu_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_happy_cms__menu_item_translation ADD CONSTRAINT FK_DBFC64242C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_happy_cms__menu_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE happy_cms_page__page_route ADD CONSTRAINT FK_875B8BC5C4663E4 FOREIGN KEY (page_id) REFERENCES sylius_happy_cms__page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE happy_cms_page__page_route ADD CONSTRAINT FK_875B8BC534ECB4E6 FOREIGN KEY (route_id) REFERENCES orm_routes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_happy_cms__page_translation ADD CONSTRAINT FK_A74F201D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_happy_cms__page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_happy_cms__shared_block_translation ADD CONSTRAINT FK_62715C802C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_happy_cms__shared_block (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_happy_cms__config_translation DROP FOREIGN KEY FK_D73724B62C2AC5D3');
        $this->addSql('ALTER TABLE sylius_happy_cms__menu_item DROP FOREIGN KEY FK_52C0AB66CCD7E912');
        $this->addSql('ALTER TABLE sylius_happy_cms__menu_item DROP FOREIGN KEY FK_52C0AB66727ACA70');
        $this->addSql('ALTER TABLE sylius_happy_cms__menu_item_translation DROP FOREIGN KEY FK_DBFC64242C2AC5D3');
        $this->addSql('ALTER TABLE happy_cms_page__page_route DROP FOREIGN KEY FK_875B8BC5C4663E4');
        $this->addSql('ALTER TABLE happy_cms_page__page_route DROP FOREIGN KEY FK_875B8BC534ECB4E6');
        $this->addSql('ALTER TABLE sylius_happy_cms__page_translation DROP FOREIGN KEY FK_A74F201D2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_happy_cms__shared_block_translation DROP FOREIGN KEY FK_62715C802C2AC5D3');
        $this->addSql('DROP TABLE sylius_happy_cms__config');
        $this->addSql('DROP TABLE sylius_happy_cms__config_translation');
        $this->addSql('DROP TABLE sylius_happy_cms__menu');
        $this->addSql('DROP TABLE sylius_happy_cms__menu_item');
        $this->addSql('DROP TABLE sylius_happy_cms__menu_item_translation');
        $this->addSql('DROP TABLE sylius_happy_cms__page');
        $this->addSql('DROP TABLE happy_cms_page__page_route');
        $this->addSql('DROP TABLE sylius_happy_cms__page_translation');
        $this->addSql('DROP TABLE sylius_happy_cms__shared_block');
        $this->addSql('DROP TABLE sylius_happy_cms__shared_block_translation');
    }
}
