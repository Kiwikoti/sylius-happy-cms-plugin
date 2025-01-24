<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Blocks;

use Adeliom\SyliusEasyCrudPlugin\Form\TextEditorType;
use Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock;
use Adeliom\SyliusHappyCMSPlugin\Form\Type\ButtonEmbeddableType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CtaBlockType extends AbstractBlock
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => $this->translator->trans('sylius_happy_cms.blocks.cta.fields.title', [], ''),
            ])
            ->add('wysiwyg', TextEditorType::class, [
                'required' => true,
                'label' => $this->translator->trans('sylius_happy_cms.blocks.cta.fields.wysiwyg', [], ''),
            ])
            ->add('cta_one', ButtonEmbeddableType::class, [
                'required' => false,
                'label' => $this->translator->trans('sylius_happy_cms.blocks.cta.fields.cta_one', [], ''),
                'fields' => ['label', 'link'],
            ])
            ->add('cta_two', ButtonEmbeddableType::class, [
                'required' => false,
                'label' => $this->translator->trans('sylius_happy_cms.blocks.cta.fields.cta_two', [], ''),
                'fields' => ['label', 'link'],
            ])
        ;
    }

    public function getName(): string
    {
        return $this->translator->trans('sylius_happy_cms.blocks.cta.name', [], '');
    }

    public function getTab(): string
    {
        return $this->translator->trans('admin.tabs.flex_blocks', [], '');
    }

    public function getIcon(): string
    {
        return '<img class="card-img-top" src="/static/admin/blocks/CtaBlockType.jpg" alt="">';
    }

    public function getFrontEndTemplatePath(): string
    {
        return '@SyliusHappyCMSPlugin/front/blocks/cta_block.html.twig';
    }
}
