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
            ->setLabel('sylius_happy_cms.admin.menu.cms')
            ->setLabelAttribute('icon', 'tabler:brand-pagekit')
            ->setExtra('always_open', true)
        ;
        $newSubmenu
            ->addChild('happy_cms_page', ['route' => 'sylius_happy_cms_admin_page_index'])
            ->setLabel('sylius_happy_cms.admin.menu.pages')
            ->setLabelAttribute('icon', 'tabler:brand-pagekit')
        ;
        $newSubmenu
            ->addChild('media', ['route' => 'media.index'])
            ->setLabel('sylius_happy_cms.admin.menu.medias')
            ->setLabelAttribute('icon', 'tabler:photo')
        ;
        $newSubmenu
            ->addChild('happy_cms_menu', ['route' => 'sylius_happy_cms_admin_menu_index'])
            ->setLabel('sylius_happy_cms.admin.menu.menus')
            ->setLabelAttribute('icon', 'tabler:menu-deep')
        ;
        $newSubmenu
            ->addChild('happy_cms_block', ['route' => 'sylius_happy_cms_admin_shared_block_index'])
            ->setLabel('sylius_happy_cms.admin.menu.shared_blocks')
            ->setLabelAttribute('icon', 't:box-bold')
        ;
        //$newSubmenu
        //    ->addChild('happy_cms_route', ['route' => 'sylius_happy_cms_admin_route_index'])
        //    ->setLabel('sylius_happy_cms.admin.menu.routes')
        //    ->setLabelAttribute('icon', 'file')
        //;
        $newSubmenu
            ->addChild('happy_cms_redirect_route', ['route' => 'sylius_happy_cms_admin_redirect_route_index'])
            ->setLabel('sylius_happy_cms.admin.menu.redirections')
            ->setLabelAttribute('icon', 'tabler:directions')
        ;
        $newSubmenu
            ->addChild('happy_cms_redirect_config', ['route' => 'sylius_happy_cms_admin_config_index'])
            ->setLabel('sylius_happy_cms.admin.menu.configurations')
            ->setLabelAttribute('icon', 'tabler:settings-code')
        ;

        $children = $menu->getChildren();

        $dashboardMenu = $children['dashboard'];
        unset($children['dashboard']);

        $cms = $children['adeliom-sylius-cms'];
        unset($children['adeliom-sylius-cms']);

        $menu->reorderChildren(array_keys(
            ['dashboard' => $dashboardMenu] +
            ['adeliom-sylius-cms' => $cms] +
        $children,
        ));
    }
}
