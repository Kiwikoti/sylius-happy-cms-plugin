<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Twig\SharedBlock;

use Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock\Helper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SharedBlockExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('happy_cms_block', [Helper::class, 'renderBlock'], ['is_safe' => ['js', 'html'], 'needs_context' => true, 'needs_environment' => true]),
            new TwigFunction('happy_cms_block_assets', [Helper::class, 'includeAssets'], ['is_safe' => ['js', 'html'], 'needs_context' => true, 'needs_environment' => true]),
        ];
    }
}
