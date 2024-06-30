<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock;

use Doctrine\Common\Collections\ArrayCollection;

class SharedBlockCollection
{
    /** @var iterable<SharedBlockInterface> */
    protected $blocks = [];

    public function __construct(iterable $blocks)
    {
        foreach ($blocks as $block) {
            $this->blocks[$block::class] = $block;
        }

        uasort($this->blocks, static fn ($a, $b) => $a->getPosition() <=> $b->getPosition());
        $this->blocks = new ArrayCollection($this->blocks);
    }

    public function enabledSupportFilter()
    {
        $this->filterSupportedBlocks();

        return $this;
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param array $blockTypes
     *
     * @return array
     */
    public function getAllowedBlocks(?array $blockTypes)
    {
        $blocks = $this->getBlocks();

        if (empty($blockTypes)) {
            return $blocks;
        }

        return $blocks->filter(static fn (SharedBlockInterface $block, $type) => in_array($type, $blockTypes));
    }

    private function filterSupportedBlocks(): void
    {
        /*if (null !== $this->entityDto) {
            $this->blocks = $this->blocks->filter(fn (BlockInterface $block, $type) => $block->supports($this->entityDto->getFqcn(), $this->entityDto->getInstance()));
        }*/
    }
}
