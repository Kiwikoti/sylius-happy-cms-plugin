<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\EventListener\Media;

use Adeliom\SyliusHappyCMSPlugin\Entity\Media\MediaInterface;
use Adeliom\SyliusHappyCMSPlugin\Services\Media\MediaManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use League\Flysystem\FilesystemException;

class MediaSubscriber
{
    public function __construct(private MediaManager $manager)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        /** @var MediaInterface $media */
        $media = $args->getObject();
        if (!$media instanceof MediaInterface) {
            return;
        }

        if ($args->hasChangedField('folder')) {
            $oldPath = ($args->getOldValue('folder') ? $args->getOldValue('folder')->getPath() : '') . \DIRECTORY_SEPARATOR . $media->getSlug();
            $newPath = ($args->getNewValue('folder') ? $args->getNewValue('folder')->getPath() : '') . \DIRECTORY_SEPARATOR . $media->getSlug();
            $this->manager->move($oldPath, $newPath);
        }
    }
}
