<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock;

class SharedBlockCollection
{
    /** @var array<string, SharedBlockTypeInterface> */
    protected array $blocks;

    /**
     * @param iterable<SharedBlockTypeInterface> $blocksList
     */
    public function __construct(iterable $blocksList)
    {
        $blocks = [];
        foreach ($blocksList as $block) {
            $blocks[$block::class] = $block;
        }

        uasort($blocks, static fn ($a, $b) => $a->getPosition() <=> $b->getPosition());
        $this->blocks = $blocks;
    }

    public function enabledSupportFilter(): self
    {
        $this->filterSupportedBlocks();

        return $this;
    }

    /**
     * @return array<SharedBlockTypeInterface>
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * @param array<SharedBlockTypeInterface> $blockTypes
     *
     * @return array<SharedBlockTypeInterface>
     */
    public function getAllowedBlocks(?array $blockTypes): array
    {
        $blocks = $this->getBlocks();

        if (empty($blockTypes)) {
            return $blocks;
        }

        return array_filter(
            $blocks,
            static fn (SharedBlockTypeInterface $block, string $type) => in_array($type, $blockTypes),
            \ARRAY_FILTER_USE_BOTH,
        );
    }

    private function filterSupportedBlocks(): void
    {
        /*if (null !== $this->entityDto) {
            $this->blocks = $this->blocks->filter(fn (BlockInterface $block, $type) => $block->supports($this->entityDto->getFqcn(), $this->entityDto->getInstance()));
        }*/
    }
}
