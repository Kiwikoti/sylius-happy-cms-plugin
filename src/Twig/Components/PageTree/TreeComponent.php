<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Twig\Components\PageTree;

use Adeliom\SyliusHappyCMSPlugin\Doctrine\Query\Page\AllPagesInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Page\PageInterface;
use Adeliom\SyliusHappyCMSPlugin\Repository\Page\PageRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\TwigHooks\LiveComponent\HookableLiveComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;

class TreeComponent
{
    use DefaultActionTrait;
    use HookableLiveComponentTrait;
    use TemplatePropTrait;

    public function __construct(
        protected readonly AllPagesInterface $allPages,
        protected readonly EntityManagerInterface $entityManager,
    ) {
    }

    /** @return array<array-key, mixed> */
    public function getTree(): array
    {
        return $this->buildTree($this->allPages->getArrayResult());
    }

    #[LiveAction]
    public function moveUp(#[LiveArg] int $pageId): void
    {
        $pageRepository = $this->entityManager->getRepository(PageInterface::class);
        $pageToBeMoved = $pageRepository->find($pageId);

        if ($pageToBeMoved->getPosition() > 0) {
            $pageToBeMoved->setPosition($pageToBeMoved->getPosition() - 1);
            $this->entityManager->flush();
        }
    }

    #[LiveAction]
    public function moveDown(#[LiveArg] int $pageId): void
    {
        $pageRepository = $this->entityManager->getRepository(PageInterface::class);
        $pageToBeMoved = $pageRepository->find($pageId);

        $pageToBeMoved->setPosition($pageToBeMoved->getPosition() + 1);
        $this->entityManager->flush();
    }

    /**
     * @param array<array-key, mixed> $pages
     *
     * @return array<array-key, mixed>
     */
    private function buildTree(array $pages): array
    {
        $tree = [];
        $children = [];

        foreach ($pages as $page) {
            $treeChild = [
                'id' => $page['id'],
                'name' => $page['name'],
                'children' => $children[$page['id']] ?? [],
            ];

            if (null !== $page['parent_id']) {
                $children[$page['parent_id']][] = $treeChild;
            } else {
                $tree[] = $treeChild;
            }
        }

        return $tree;
    }
}
