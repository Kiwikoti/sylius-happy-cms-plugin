<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Twig\Components\MenuItemTree;

use Adeliom\SyliusHappyCMSPlugin\Doctrine\Query\Menu\AllMenuItemsInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuItemInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\TwigHooks\LiveComponent\HookableLiveComponentTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;

class TreeComponent
{
    use DefaultActionTrait;
    use HookableLiveComponentTrait;
    use TemplatePropTrait;

    public function __construct(
        protected readonly AllMenuItemsInterface $allMenuItems,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly RequestStack $requestStack,
    ) {
    }

    /** @return array<array-key, mixed> */
    public function getTree(): array
    {
        $id = (int) $this->requestStack->getCurrentRequest()->get('menu_id') ?? '0';

        return $this->buildTree($this->allMenuItems->getArrayResult($id));
    }

    #[LiveAction]
    public function moveUp(#[LiveArg] int $menuItemId): void
    {
        $menuItemRepository = $this->entityManager->getRepository(MenuItemInterface::class);
        $menuItemToBeMoved = $menuItemRepository->find($menuItemId);

        if ($menuItemToBeMoved->getPosition() > 0) {
            $menuItemToBeMoved->setPosition($menuItemToBeMoved->getPosition() - 1);
            $this->entityManager->flush();
        }
    }

    #[LiveAction]
    public function moveDown(#[LiveArg] int $menuItemId): void
    {
        $menuItemRepository = $this->entityManager->getRepository(MenuItemInterface::class);
        $menuItemToBeMoved = $menuItemRepository->find($menuItemId);

        $menuItemToBeMoved->setPosition($menuItemToBeMoved->getPosition() + 1);
        $this->entityManager->flush();
    }

    /**
     * @param array<array-key, mixed> $menuItems
     *
     * @return array<array-key, mixed>
     */
    private function buildTree(array $menuItems): array
    {
        $tree = [];
        $children = [];

        foreach ($menuItems as $menuItem) {
            $treeChild = [
                'id' => $menuItem['id'],
                'name' => $menuItem['name'],
                'children' => $children[$menuItem['id']] ?? [],
            ];

            if (null !== $menuItem['parent_id']) {
                $children[$menuItem['parent_id']][] = $treeChild;
            } else {
                $tree[] = $treeChild;
            }
        }

        return $tree;
    }
}
