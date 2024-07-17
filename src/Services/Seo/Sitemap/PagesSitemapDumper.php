<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap;

use Adeliom\SyliusHappyCMSPlugin\Entity\Page\PageInterface;
use Adeliom\SyliusHappyCMSPlugin\Repository\Page\PageRepository;

class PagesSitemapDumper extends AbstractSitemapDumper
{
    public function __construct(
        private readonly PageRepository $pageRepository,
    ) {
    }

    public static function getSitemapSection(): string
    {
        return 'pages';
    }

    /**
     * @return array<int, PageInterface|object>
     */
    public function getEntities(): array
    {
        return $this->pageRepository->findAll();
    }
}
