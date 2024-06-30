<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Block;

use Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock;
use Symfony\Contracts\EventDispatcher\Event;

class BlockRender extends Event
{
    public function __construct(
        private AbstractBlock $block,
        private array $datas,
        private array $assets,
    ) {
    }

    public function getBlock(): AbstractBlock
    {
        return $this->block;
    }

    public function getDatas(): array
    {
        return $this->datas;
    }

    public function getAssets(): array
    {
        return $this->assets;
    }

    public function setBlock(AbstractBlock $block): void
    {
        $this->block = $block;
    }

    public function setDatas(array $datas): void
    {
        $this->datas = $datas;
    }

    public function setAssets(array $assets): void
    {
        $this->assets = $assets;
    }
}
