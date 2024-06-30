<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Controller\SharedBlock;

use Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock\AbstractSharedBlock;
use Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock\SharedBlockCollection;
use Adeliom\SyliusHappyCMSPlugin\SharedBlock\SharedBlockType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Environment;

class SharedBlockController implements ServiceSubscriberInterface
{
    public function __construct(
        private Environment $twig,
        private SharedBlockCollection $sharedBlockCollection,
    ) {
    }

    #[Route('/admin/shared-blocks/select', name: 'happy_cms_admin_shared_block_select', methods: ['GET'])]
    public function select(): Response
    {
        return new Response($this->twig->render('@SyliusHappyCMSPlugin/shared_block/select.html.twig', [
            'blocks' => $this->sharedBlockCollection->getBlocks()->filter(static fn (AbstractSharedBlock $block) => $block::class !== SharedBlockType::class),
        ]));
    }

    public static function getSubscribedServices(): array
    {
        return [];
    }
}
