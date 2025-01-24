<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Blocks\SubType;

use Adeliom\SyliusEasyCrudPlugin\Form\TextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccordionItemEmbeddableType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, [
            'required' => false,
            'label' => $this->translator->trans('admin.blocks.accordion.fields.item.title', [], 'blocks'),
        ]);

        $builder->add('content', TextEditorType::class, [
            'required' => false,
            'label' => $this->translator->trans('admin.blocks.accordion.fields.item.content', [], 'blocks'),
        ]);
    }
}
