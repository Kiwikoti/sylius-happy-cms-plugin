<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\EventListener\Media;

use Adeliom\SyliusHappyCMSPlugin\Entity\Media\FolderInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Media\MediaInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * This class adds automatically the ManyToOne and OneToMany relations in Page and Category entities,
 * because it's normally impossible to do so in a mapped superclass.
 */
class DoctrineMappingListener
{
    public function __construct(
        private readonly string $mediaClass,
        private readonly string $folderClass,
    ) {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        /**
         * @var ClassMetadata<FolderInterface|MediaInterface> $classMetadata
         */
        $classMetadata = $eventArgs->getClassMetadata();
        $reflexionClass = $classMetadata->getReflectionClass();

        if (in_array(FolderInterface::class, $reflexionClass->getInterfaces())) {
            $this->processParent($classMetadata, $this->folderClass);
            $this->processChildren($classMetadata, $this->folderClass);
            $this->processMedias($classMetadata, $this->mediaClass);
        }

        if (in_array(MediaInterface::class, $reflexionClass->getInterfaces())) {
            $this->processFolder($classMetadata, $this->folderClass);
        }
    }

    /**
     * Declare self-bidirectionnal mapping for parent.
     *
     * @param ClassMetadata<FolderInterface|MediaInterface> $classMetadata
     */
    private function processParent(ClassMetadata $classMetadata, string $class): void
    {
        if (!$classMetadata->hasAssociation('parent')) {
            $classMetadata->mapManyToOne([
                'fieldName' => 'parent',
                'targetEntity' => $class,
                'inversedBy' => 'children',
                'cascade' => ['persist', 'detach'],
                'joinColumns' => [
                    [
                        'name' => 'parent_id',
                        'referencedColumnName' => 'id',
                        'nullable' => 'true',
                        'onDelete' => 'SET NULL',
                    ],
                ],
            ]);
        }
    }

    /**
     * Declare self-bidirectionnal mapping for children.
     *
     * @param ClassMetadata<FolderInterface|MediaInterface> $classMetadata
     */
    private function processChildren(ClassMetadata $classMetadata, string $class): void
    {
        if (!$classMetadata->hasAssociation('children')) {
            $classMetadata->mapOneToMany([
                'fieldName' => 'children',
                'targetEntity' => $class,
                'mappedBy' => 'parent',
                'cascade' => ['persist', 'remove'],
            ]);
        }
    }

    /**
     * @param ClassMetadata<FolderInterface|MediaInterface> $classMetadata
     */
    private function processMedias(ClassMetadata $classMetadata, string $class): void
    {
        if (!$classMetadata->hasAssociation('medias')) {
            $classMetadata->mapOneToMany([
                'fieldName' => 'medias',
                'targetEntity' => $class,
                'mappedBy' => 'folder',
                'cascade' => ['persist', 'remove'],
            ]);
        }
    }

    /**
     * @param ClassMetadata<FolderInterface|MediaInterface> $classMetadata
     */
    private function processFolder(ClassMetadata $classMetadata, string $class): void
    {
        if (!$classMetadata->hasAssociation('folder')) {
            $classMetadata->mapManyToOne([
                'fieldName' => 'folder',
                'targetEntity' => $class,
                'inversedBy' => 'medias',
                'cascade' => ['persist'],
            ]);
        }
    }
}
