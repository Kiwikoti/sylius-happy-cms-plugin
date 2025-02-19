<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Block\SubType;

use Adeliom\SyliusEasyCrudPlugin\Form\IconType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class KeyFeatureEmbeddableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('icon', IconType::class, [
                'required' => false,
                'label' => 'sylius_happy_cms.blocks.key_features.fields.feature.icon',
            ])
            ->add('key', TextType::class, [
                'required' => false,
                'label' => 'sylius_happy_cms.blocks.key_features.fields.feature.key',
            ])
            ->add('text', TextType::class, [
                'required' => false,
                'label' => 'sylius_happy_cms.blocks.key_features.fields.feature.text',
            ]);
    }
}
