<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\ContextProvider;

use Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock\SharedBlockCollection;
use Sylius\Bundle\UiBundle\ContextProvider\ContextProviderInterface;
use Sylius\Bundle\UiBundle\Registry\TemplateBlock;

class CreateSharedBlockContextProvider implements ContextProviderInterface
{
    public function __construct(
        private SharedBlockCollection $sharedBlockCollection,
    ) {
    }

    public function provide(array $templateContext, TemplateBlock $templateBlock): array
    {
        return [
            'blocks' => $this->sharedBlockCollection->getBlocks(),
        ];
    }

    public function supports(TemplateBlock $templateBlock): bool
    {
        return 'sylius.cms.shared_block.choose' === $templateBlock->getEventName() && 'content' === $templateBlock->getName();
    }
}
