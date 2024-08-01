<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\EventListener\Menu;

use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuItemInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * This class adds automatically the ManyToOne and OneToMany relations in Page and Category entities,
 * because it's normally impossible to do so in a mapped superclass.
 */
class DoctrineMappingListener
{
    public function __construct(
        private readonly string $menuClass,
        private readonly string $menuItemClass,
    ) {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        /**
         * @var ClassMetadata<MenuInterface|MenuItemInterface> $classMetadata
         */
        $classMetadata = $eventArgs->getClassMetadata();
        $reflexionClass = $classMetadata->getReflectionClass();

        if (in_array(MenuItemInterface::class, $reflexionClass->getInterfaces())) {
            $this->processMenuItemMetadata($classMetadata);
        }

        if (in_array(MenuInterface::class, $reflexionClass->getInterfaces())) {
            $this->processMenuMetadata($classMetadata);
        }
    }

    /**
     * @param ClassMetadata<MenuInterface|MenuItemInterface> $classMetadata
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
     * @param ClassMetadata<MenuInterface|MenuItemInterface> $classMetadata
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
