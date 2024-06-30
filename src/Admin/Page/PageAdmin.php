<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Page;

use Adeliom\SyliusEasyCrudPlugin\Admin\AdminInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Page\Page;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class PageAdmin extends AbstractPageAdmin implements ServiceSubscriberInterface, AdminInterface
{
    public static function getName(): string
    {
        return 'sylius_happy_cms_page_admin';
    }

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }
}
