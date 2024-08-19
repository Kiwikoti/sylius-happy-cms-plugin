<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Repository\Menu;

use Adeliom\SyliusEasyCrudPlugin\Repository\TranslationRepositoryInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\Menu;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuItemInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @extends RepositoryInterface<MenuItemInterface>
 */
interface MenuItemRepositoryInterface extends RepositoryInterface, TranslationRepositoryInterface
{
    /**
     * @return array<MenuItemInterface>|QueryBuilder ($returnQueryBuilder ? QueryBuilder : MenuItem[])
     */
    public function getByMenu(Menu $menu, bool $returnQueryBuilder = false): array|QueryBuilder;

    public function filterByMenu(int $menuId, string $locale): QueryBuilder;

    public function findPreviousMenuItem(MenuItemInterface $menuItem): ?MenuItemInterface;

    public function findNextMenuItem(MenuItemInterface $menuItem): ?MenuItemInterface;
}
