<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Field;

use Adeliom\SyliusHappyCMSPlugin\Form\MediaType;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\FieldInterface;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\FieldTrait;

class MediaField implements FieldInterface
{
    use FieldTrait;

    /**
     * @param string|true|null $label
     */
    public static function new(string $propertyName, $label = null): self
    {
        $field = (new self());
        $field
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setGridTemplatePath('@SyliusHappyCMSPlugin/media/field/media/grid.html.twig')
            ->setShowTemplatePath('@SyliusHappyCMSPlugin/media/field/media/show.html.twig')
            ->addFormThemes(MediaType::configureAdminFormThemes())
            ->addJsFiles(MediaType::configureAdminAssets()['js'])
            ->addCssFiles(MediaType::configureAdminAssets()['css'])
            ->setFormType(MediaType::class)
            ->addCssClass('field-happy-cms-media')
        ;
        return $field;
    }
}
