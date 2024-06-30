<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap;

use Adeliom\SyliusEasyCrudPlugin\Traits\EntityTimestampableTrait;
use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;

abstract class AbstractSitemapDumper implements SitemapDumperInterface
{
    abstract public static function getSitemapSection(): string;

    public static function getSitemapRoute(): string
    {
        return 'cmf_routing_object';
    }

    /** @inheritdoc */
    public static function getSitemapRouteParams(CmsRoutableInterface $entity): array
    {
        return [
            '_route_object' => $entity->getOnlineRoute(),
        ];
    }

    abstract public function getEntities(): array;

    public function getLastModifiedDate(CmsRoutableInterface $entity): ?\DateTimeInterface
    {
        if (in_array(EntityTimestampableTrait::class, class_implements($entity))) {
            return $entity->getUpdatedAt();
        }

        return null;
    }

    public function replaceUrl(string $url, CmsRoutableInterface $entity): ?string
    {
        return null;
    }
}
