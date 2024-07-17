<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap;

use Adeliom\SyliusEasyCrudPlugin\Traits\EntityTimestampableTrait;
use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

abstract class AbstractSitemapDumper implements SitemapDumperInterface
{
    abstract public static function getSitemapSection(): string;

    public static function getSitemapRoute(): string
    {
        return 'cmf_routing_object';
    }

    /**
     * @param CmsRoutableInterface $entity
     * @return array<string, ?RouteObjectInterface>
     */
    public static function getSitemapRouteParams(CmsRoutableInterface $entity): array
    {
        return [
            '_route_object' => $entity->getOnlineRoute(),
        ];
    }

    abstract public function getEntities(): array;

    public function getLastModifiedDate(CmsRoutableInterface $entity): ?\DateTimeInterface
    {
        if (in_array(EntityTimestampableTrait::class, class_implements($entity)) && method_exists($entity, 'getUpdatedAt')) {
            return $entity->getUpdatedAt();
        }

        return null;
    }

    public function replaceUrl(string $url, CmsRoutableInterface $entity): ?string
    {
        return null;
    }
}
