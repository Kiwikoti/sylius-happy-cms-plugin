<?php

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Field;

use Adeliom\SyliusHappyCMSPlugin\Form\MediaType;
use Adeliom\SyliusHappyCMSPlugin\Form\Seo\SeoType;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\FieldInterface;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\FieldTrait;
use Adeliom\SyliusEasyCrudPlugin\Form\AdminFormTypeInterface;

final class SEOField implements FieldInterface
{
    use FieldTrait;

    /**
     * @param string|false|null $label
     */
    public static function new(string $propertyName, $label = false): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->hideOnIndex()
            ->setFormType(SeoType::class)
            ->addAssets(SeoType::configureAdminAssets())
            ->addFormThemes(SeoType::configureAdminFormThemes())
            ->setShowTemplatePath('@SyliusHappyCMSPlugin/field/seo/show.html.twig')
        ;
    }
}
