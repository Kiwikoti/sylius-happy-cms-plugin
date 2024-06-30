<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap;

use Adeliom\SyliusHappyCMSPlugin\Repository\Page\PageRepository;

class PagesSitemapDumper extends AbstractSitemapDumper
{
    public function __construct(
        private PageRepository $pageRepository,
    ) {
    }

    public static function getSitemapSection(): string
    {
        return 'pages';
    }

    public function getEntities(): array
    {
        return $this->pageRepository->findAll();
    }
}
