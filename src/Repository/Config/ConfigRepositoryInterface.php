<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Repository\Config;

use Adeliom\SyliusEasyCrudPlugin\Repository\TranslationRepositoryInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Config\ConfigInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @extends RepositoryInterface<ConfigInterface>
 */
interface ConfigRepositoryInterface extends RepositoryInterface, TranslationRepositoryInterface
{
}
