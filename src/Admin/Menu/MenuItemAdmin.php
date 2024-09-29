<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Menu;

use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuItemInterface;

class MenuItemAdmin extends AbstractMenuItemAdmin
{
    public static function getName(): string
    {
        return 'sylius_happy_cms_menu_item_admin';
    }

    public static function getEntityFqcn(): string
    {
        return MenuItemInterface::class;
    }
}
