<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Block;

use Adeliom\SyliusHappyCMSPlugin\Factory\Block\BlockTypeInterface;
use Symfony\Contracts\EventDispatcher\Event;

class BlockRender extends Event
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $assets
     */
    public function __construct(
        private BlockTypeInterface $block,
        private array $data,
        private array $assets,
    ) {
    }

    public function getBlock(): BlockTypeInterface
    {
        return $this->block;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAssets(): array
    {
        return $this->assets;
    }

    public function setBlock(BlockTypeInterface $block): void
    {
        $this->block = $block;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array<string, mixed> $assets
     */
    public function setAssets(array $assets): void
    {
        $this->assets = $assets;
    }
}
