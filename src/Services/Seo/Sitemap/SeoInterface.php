<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap;


use Adeliom\SyliusHappyCMSPlugin\Entity\Seo\Seo;

interface SeoInterface
{
    public function setSEO(Seo $seo): void;

    public function getSEO(): SEO;
}
