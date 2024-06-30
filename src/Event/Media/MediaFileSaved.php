<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Media;

class MediaFileSaved extends MediaFileUploaded
{
    /**
     * @var string
     */
    public const NAME = 'em.file.saved';
}
