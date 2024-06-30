<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Factory\Block;

use Adeliom\SyliusHappyCMSPlugin\Event\Block\BlockRender;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;
use Twig\Markup;

class Helper
{
    /**
     * This property is a state variable holdings all assets used by the block for the current PHP request
     * It is used to correctly render the javascripts and stylesheets tags on the main layout.
     */
    private array $assets = [
        'js' => [],
        'css' => [],
        'webpack' => [],
    ];

    private array $traces = [];

    public function __construct(
        /**
         * @readonly
         */
        private Environment $twig,
        /**
         * @readonly
         */
        private EventDispatcherInterface $eventDispatcher,
        /**
         * @readonly
         */
        private BlockCollection $collection,
    ) {
    }

    /**
     * @return mixed[]|string
     */
    public function includeAssets(): array|string
    {
        $html = '';

        if (!empty($this->assets['css'])) {
            $html .= "<style media='all'>";
            foreach ($this->assets['css'] as $stylesheet) {
                $html .= "\n" . sprintf('@import url(%s);', $stylesheet);
            }

            $html .= "\n</style>";
        }

        if (!empty($this->assets['js'])) {
            foreach ($this->assets['js'] as $javascript) {
                $html .= "\n" . sprintf('<script src="%s" type="text/javascript"></script>', $javascript);
            }
        }

        if (!empty($this->assets['webpack'])) {
            foreach ($this->assets['webpack'] as $webpack) {
                try {
                    $html .= "\n" . $this->twig->createTemplate(sprintf("{{ encore_entry_link_tags('%s') }}", $webpack))->render();
                    $html .= "\n" . $this->twig->createTemplate(sprintf("{{ encore_entry_script_tags('%s') }}", $webpack))->render();
                } catch (LoaderError|SyntaxError) {
                    $html .= '';
                }
            }
        }

        return $html;
    }

    /**
     * Returns the rendering traces.
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    private function startTracing(BlockInterface $block): array
    {
        return [
            'id' => uniqid(),
            'name' => $block->getName(),
            'type' => $block::class,
            'position' => null,
            'datas' => [],
            'assets' => [
                'js' => [],
                'css' => [],
                'webpack' => [],
            ],
        ];
    }

    private function stopTracing($id, array $stats): void
    {
        $this->traces[$id] = $stats;
    }

    /**
     * @param array<mixed> $datas
     * @param bool $preview Set to true if you want to display all block, otherwise only display block with "block_published" = 1
     * @param array<mixed> $extra
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderBlock(array $datas, bool $preview = false, array $extra = []): ?Markup
    {
        if ((int) ($datas['block_published'] ?? null) === 0 && $preview === false) {
            return null;
        }

        $block = $this->collection->getBlocks()[$datas['block_type']];
        $stats = $this->startTracing($block);
        $blockType = $datas['block_type'];
        $defaultAssets = $block->configureAssets();

        $event = $this->eventDispatcher->dispatch(new BlockRender($block, $datas, $defaultAssets));

        $block = $event->getBlock();
        $blockDatas = $event->getDatas();

        if (isset($blockDatas['block_type'])) {
            unset($blockDatas['block_type']);
        }

        if (isset($blockDatas['position'])) {
            $stats['position'] = $blockDatas['position'];
            unset($blockDatas['position']);
        }

        // Add a way to automatically set an ID (base on loop index when the page is rendered)
        if (empty($blockDatas['attr_id'])) {
            global $blockLoopIndex;
            if (empty($blockLoopIndex)) {
                $blockLoopIndex = 0;
            }

            ++$blockLoopIndex;
            $blockDatas['attr_id'] = 'block-' . $blockLoopIndex;
        }

        $stats['settings'] = $blockDatas;
        $stats['assets'] = $event->getAssets();

        $this->assets = array_merge_recursive($this->assets, $stats['assets']);

        $this->stopTracing($stats['id'], $stats);

        return new Markup($this->twig->render($block->getFrontEndTemplatePath(), array_merge([
            'block' => $datas,
            'blockType' => $blockType,
            'settings' => $blockDatas,
        ], $extra)), 'UTF-8');
    }
}
