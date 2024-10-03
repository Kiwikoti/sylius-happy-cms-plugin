<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Config;

use Adeliom\SyliusHappyCMSPlugin\Entity\Config\Config;

class ConfigAdmin extends AbstractConfigAdmin
{
    public static function getName(): string
    {
        return 'sylius_happy_cms_config_admin';
    }

    public static function getEntityFqcn(): string
    {
        return Config::class;
    }
}
