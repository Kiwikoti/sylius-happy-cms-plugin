<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\SharedBlock;

use Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock;
use Adeliom\SyliusHappyCMSPlugin\Factory\Block\BlockInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SharedBlockType extends AbstractBlock implements BlockInterface
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('test', TextType::class, [])
        ;
    }

    public function getName(): string
    {
        return 'Shared block';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getIcon(): string | array
    {
        return '';
    }

    public function getFrontEndTemplatePath(): string
    {
        return '@EasyBlock/editor/shared_block.html.twig';
    }
}
