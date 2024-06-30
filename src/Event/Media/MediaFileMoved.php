<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Media;

class MediaFileMoved extends MediaFileRenamed
{
    /**
     * @var string
     */
    public const NAME = 'em.file.moved';
}
