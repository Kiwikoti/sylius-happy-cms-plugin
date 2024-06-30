<?php

namespace Adeliom\SyliusHappyCMSPlugin\EventListener\Menu;

use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\Menu;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuItem;
use Adeliom\SyliusEasyCrudPlugin\Enum\ThreeStateStatusEnum;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuCreationListener
{
    public function __construct(
        protected string $menuClass,
        protected string $menuItemClass,
        protected LocaleProviderInterface $localeProvider,
        protected TranslatorInterface $translator,
    ) {
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function prePersist(Menu $menu): void
    {
        /**
         * @var MenuItem $rootItem
         */
        $rootItem = new $this->menuItemClass();
        $rootItem->setMenu($menu);
        $rootItem->setPublishState(ThreeStateStatusEnum::PUBLISHED());
        $rootItem->setPosition(0);

        $menu->addItem($rootItem);

        foreach ($this->localeProvider->getAvailableLocalesCodes() as $locale) {
            $menuItemTranslationClass = $this->menuItemClass::getTranslationClass();
            $translation = new $menuItemTranslationClass();
            $translation->setLocale($locale);
            $translation->setName(
                $this->translator->trans('happy_cms.menu_item.admin.data.menu_item_root', locale: $locale)
            );
            $rootItem->addTranslation($translation);
        }
    }
}
