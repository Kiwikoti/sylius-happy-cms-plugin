<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Block;

use Adeliom\SyliusEasyCrudPlugin\Form\TextEditorType;
use Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock;
use Symfony\Component\Form\FormBuilderInterface;

class SeoBlockType extends AbstractBlock
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('wysiwyg', TextEditorType::class, [
                'required' => true,
                'label' => 'sylius_happy_cms.blocks.seo.fields.wysiwyg',
            ]);
    }

    public function getName(): string
    {
        return 'sylius_happy_cms.blocks.seo.name';
    }

    public function getTab(): string
    {
        return 'sylius_happy_cms.blocks.tabs.text_blocks';
    }

    public function getIcon(): string
    {
        return '<img class="card-img-top" src="/static/admin/blocks/SeoBlockType.jpg" alt="">';
    }

    public function getFrontEndTemplatePath(): string
    {
        return '@SyliusHappyCMSPlugin/front/blocks/seo_block.html.twig';
    }
}
