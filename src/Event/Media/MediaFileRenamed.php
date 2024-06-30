<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Media;

use Symfony\Contracts\EventDispatcher\Event;

class MediaFileRenamed extends Event
{
    /**
     * @var string
     */
    public const NAME = 'em.file.renamed';

    public function __construct(
        public string $oldPath,
        public string $newPath,
    ) {
    }
}
