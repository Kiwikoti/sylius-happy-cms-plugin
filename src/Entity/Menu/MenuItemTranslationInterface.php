<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Menu;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface MenuItemTranslationInterface extends TranslationInterface, ResourceInterface, \Stringable
{
    public function setName(?string $name): void;
}
