<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Seo;

use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;
use Symfony\Contracts\EventDispatcher\Event;

class AfterSitemapEntities extends Event
{
    /** @param array<CmsRoutableInterface> $entities */
    public function __construct(
        private ?array $entities = [],
    ) {
    }

    /** @return array<CmsRoutableInterface>|null  */
    public function getEntities(): ?array
    {
        return $this->entities;
    }

    /** @param array<CmsRoutableInterface> $entities */
    public function setEntities(?array $entities): void
    {
        $this->entities = $entities;
    }
}
