<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Page;

use Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap\SeoInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface PageTranslationInterface extends TranslationInterface, ResourceInterface, SeoInterface, \Stringable
{
    public function getSlug(): ?string;

    public function getName(): ?string;
}
