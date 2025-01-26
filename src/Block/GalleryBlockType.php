<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Block;

use Adeliom\SyliusEasyCrudPlugin\Form\SortableCollectionType;
use Adeliom\SyliusEasyCrudPlugin\Form\TextEditorType;
use Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock;
use Adeliom\SyliusHappyCMSPlugin\Form\MediaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GalleryBlockType extends AbstractBlock
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => $this->translator->trans('sylius_happy_cms.blocks.gallery.fields.title', [], ''),
            ])
            ->add('wysiwyg', TextEditorType::class, [
                'required' => true,
                'label' => $this->translator->trans('sylius_happy_cms.blocks.gallery.fields.wysiwyg', [], ''),
            ])
            ->add('images', SortableCollectionType::class, [
                'required' => false,
                'label' => $this->translator->trans('sylius_happy_cms.blocks.gallery.fields.images', [], ''),
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_drag' => true,
            ])
        ;
    }

    public function getName(): string
    {
        return $this->translator->trans('sylius_happy_cms.blocks.gallery.name', [], '');
    }

    public function getTab(): string
    {
        return $this->translator->trans('admin.tabs.flex_blocks', [], '');
    }

    public function getIcon(): string
    {
        return '<img class="card-img-top" src="/static/admin/blocks/GalleryBlockType.jpg" alt="">';
    }

    public function getFrontEndTemplatePath(): string
    {
        return '@SyliusHappyCMSPlugin/front/blocks/gallery_block.html.twig';
    }
}
