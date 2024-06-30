<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\EventListener\Page;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * This class adds automatically the ManyToOne and OneToMany relations in Page and Category entities,
 * because it's normally impossible to do so in a mapped superclass.
 */
class DoctrineMappingListener
{
    public function __construct(
        /**
         * @readonly
         */
        private string $pageClass,
        /**
         * @readonly
         */
        private string $menuClass,
        /**
         * @readonly
         */
        private string $menuItemClass,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [Events::loadClassMetadata];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        $isPage = is_a($classMetadata->getName(), $this->pageClass, true);
        $isMenuItem = is_a($classMetadata->getName(), $this->menuItemClass, true);
        $isMenu = is_a($classMetadata->getName(), $this->menuClass, true);

        if ($isPage) {
            $this->processParent($classMetadata, $this->pageClass);
            $this->processChildren($classMetadata, $this->pageClass);
        }

        if ($isMenuItem) {
            $this->processMenuItemMetadata($classMetadata);
        }

        if ($isMenu) {
            $this->processMenuMetadata($classMetadata);
        }
    }

    /**
     * Declare self-bidirectionnal mapping for parent.
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
                        'onDelete' => 'SET NULL',
                    ],
                ],
                'nullable' => true,
            ]);
        }
    }

    /**
     * Declare self-bidirectionnal mapping for children.
     */
    private function processChildren(ClassMetadata $classMetadata, string $class): void
    {
        if (!$classMetadata->hasAssociation('children')) {
            $classMetadata->mapOneToMany([
                'fieldName' => 'children',
                'targetEntity' => $class,
                'mappedBy' => 'parent',
            ]);
        }
    }

    /**
     * Declare self-bidirectionnal mapping for parent.
     */
    private function processMenuItemMetadata(ClassMetadata $classMetadata): void
    {
        if (!$classMetadata->hasAssociation('menu')) {
            $classMetadata->mapManyToOne([
                'fieldName' => 'menu',
                'targetEntity' => $this->menuClass,
                'inversedBy' => 'items',
            ]);
        }
        if (!$classMetadata->hasAssociation('parent')) {
            $classMetadata->mapManyToOne([
                'fieldName' => 'parent',
                'targetEntity' => $this->menuItemClass,
                'inversedBy' => 'children',
                'cascade' => ['persist', 'detach'],
                'joinColumns' => [
                    [
                        'name' => 'parent_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'CASCADE',
                    ],
                ],
                'nullable' => true,
                'orderBy' => [
                    'position' => 'ASC',
                ],
            ]);
        }

        if (!$classMetadata->hasAssociation('children')) {
            $classMetadata->mapOneToMany([
                'fieldName' => 'children',
                'targetEntity' => $this->menuItemClass,
                'mappedBy' => 'parent',
                'cascade' => ['all'],
                'orderBy' => [
                    'position' => 'ASC',
                ],
            ]);
        }
    }

    /**
     * Declare self-bidirectionnal mapping for children.
     */
    private function processMenuMetadata(ClassMetadata $classMetadata): void
    {
        if (!$classMetadata->hasAssociation('items')) {
            $classMetadata->mapOneToMany([
                'fieldName' => 'items',
                'targetEntity' => $this->menuItemClass,
                'mappedBy' => 'menu',
                'cascade' => ['all'],
            ]);
        }
    }
}
