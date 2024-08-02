<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Page;

use Adeliom\SyliusEasyCrudPlugin\Admin\AdminInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

interface PageAdminInterface extends ServiceSubscriberInterface, AdminInterface
{
}
