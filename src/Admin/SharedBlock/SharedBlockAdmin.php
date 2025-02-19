<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\SharedBlock;

use Adeliom\SyliusHappyCMSPlugin\Entity\SharedBlock\SharedBlock;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class SharedBlockAdmin extends AbstractSharedBlockAdmin implements ServiceSubscriberInterface, SharedBlockAdminInterface
{
    public static function getSubscribedServices(): array
    {
        return [];
    }

    public static function getName(): string
    {
        return 'sylius_happy_cms_shared_block_admin';
    }

    public static function getEntityFqcn(): string
    {
        return SharedBlock::class;
    }
}
