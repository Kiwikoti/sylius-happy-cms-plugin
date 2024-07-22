<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Page;

use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface PageInterface extends ResourceInterface, TranslatableInterface, CmsRoutableInterface
{
    public function getPublishState(): ?string;

    public function getSlug(): ?string;

    public function getTranslation(?string $locale = null): PageTranslationInterface;
}
