<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Media;

use Symfony\Contracts\EventDispatcher\Event;

class MediaFileDeleted extends Event
{
    /**
     * @var string
     */
    public const NAME = 'em.file.deleted';


    public function __construct(public string $filePath, public bool $isFolder)
    {
    }
}
