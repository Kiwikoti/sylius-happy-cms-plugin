<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Page;

interface PageInterface
{
    public function getPublishState(): ?string;

    public function getSlug(): ?string;

    public function getTranslation(?string $locale = null): PageTranslationInterface;
}
