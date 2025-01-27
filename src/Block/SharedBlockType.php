<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Block;

use Adeliom\SyliusEasyCrudPlugin\Form\ResourceChoiceType;
use Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock;
use Adeliom\SyliusHappyCMSPlugin\Factory\Block\BlockTypeInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class SharedBlockType extends AbstractBlock implements ServiceSubscriberInterface, BlockTypeInterface
{
    public static function getSubscribedServices(): array
    {
        return [
            ParameterBagInterface::class,
        ];
    }

    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('block', ResourceChoiceType::class, [
                'resource' => 'sylius_happy_cms.shared_block',
            ]);
    }

    public function getTab(): string
    {
        return 'sylius_happy_cms.blocks.tabs.shared_blocks';
    }

    public function getName(): string
    {
        return 'Shared block';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getIcon(): string|array
    {
        return '';
    }

    public function getFrontEndTemplatePath(): string
    {
        return '@EasyBlock/editor/shared_block.html.twig';
    }
}
