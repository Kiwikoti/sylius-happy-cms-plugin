<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\SharedBlock;

use Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock\AbstractSharedBlockType;
use Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock\SharedBlockTypeInterface;
use Adeliom\SyliusHappyCMSPlugin\Form\MediaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExampleType extends AbstractSharedBlockType implements SharedBlockTypeInterface
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [])
        ;

        $builder->add('image', MediaType::class, [
            'label' => false,
        ]);
    }

    public function getName(): string
    {
        return 'Example block';
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
        return '@SyliusHappyCMSPlugin/front/shared_blocks/example.html.twig';
    }
}
