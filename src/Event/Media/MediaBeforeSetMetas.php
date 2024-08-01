<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Media;

use Adeliom\SyliusHappyCMSPlugin\Entity\Media\MediaInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Contracts\EventDispatcher\Event;

class MediaBeforeSetMetas extends Event
{
    public const NAME = 'em.before.set.metas';

    /** @param array<string, mixed> $metas */
    public function __construct(
        public MediaInterface $entity,
        public null | string | File $source,
        private array $metas,
    ) {
    }

    public function getEntity(): MediaInterface
    {
        return $this->entity;
    }

    public function getSource(): null | string | File
    {
        return $this->source;
    }

    /** @return array<string, mixed> */
    public function getMetas(): array
    {
        return $this->metas;
    }

    /** @param array<string, mixed> $metas */
    public function setMetas(array $metas): void
    {
        $this->metas = $metas;
    }
}
