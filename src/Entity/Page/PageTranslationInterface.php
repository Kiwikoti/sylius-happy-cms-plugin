<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Page;

use Sylius\Component\Resource\Model\TranslationInterface;

interface PageTranslationInterface extends TranslationInterface
{
    public function getSlug(): ?string;
}
