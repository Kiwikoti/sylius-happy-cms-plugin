<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Menu;

use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuInterface;

class MenuAdmin extends AbstractMenuAdmin
{
    public static function getName(): string
    {
        return 'sylius_happy_cms_menu_admin';
    }

    public static function getEntityFqcn(): string
    {
        return MenuInterface::class;
    }
}
