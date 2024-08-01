<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Media;

use Symfony\Contracts\EventDispatcher\Event;

class MediaGenerateAltGroup extends Event
{
    public const NAME = 'em.file.alt.generate_alt_group';

    /** @param array<int, mixed> $files */
    public function __construct(protected array $files)
    {
        $this->files = $files;
    }

    /** @return  array<int, mixed> $files */
    public function getFiles(): array
    {
        return $this->files;
    }
}
