<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubmenu = $menu
            ->addChild('adeliom-sylius-cms')
            ->setLabel('Cms')
        ;
        $newSubmenu
            ->addChild('happy_cms_page', ['route' => 'sylius_happy_cms_admin_page_index'])
            ->setLabel('Pages')
            ->setLabelAttribute('icon', 'file')
        ;
        $newSubmenu
            ->addChild('media', ['route' => 'media.index'])
            ->setLabel('Médias')
            ->setLabelAttribute('icon', 'image')
        ;
        $newSubmenu
            ->addChild('happy_cms_menu', ['route' => 'sylius_happy_cms_admin_menu_index'])
            ->setLabel('Menus')
            ->setLabelAttribute('icon', 'bars')
        ;
        $newSubmenu
            ->addChild('happy_cms_block', ['route' => 'sylius_happy_cms_admin_shared_block_index'])
            ->setLabel('Blocs partagés')
            ->setLabelAttribute('icon', 'box')
        ;

        $children = $menu->getChildren();

        $cms = $children['adeliom-sylius-cms'];
        unset($children['adeliom-sylius-cms']);
        $menu->reorderChildren(array_keys([
            'adeliom-sylius-cms' => $cms
        ] + $children));

        $children['configuration']
            ->addChild('adeliom-sylius-easy-config', ['route' => 'sylius_happy_cms_admin_config_index'])
                ->setLabel('Configurations')
                ->setLabelAttribute('icon', 'cogs');
    }
}
