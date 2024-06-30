<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Admin\Page;

use Adeliom\SyliusEasyCrudPlugin\Admin\Field\ResourceAutocompleteChoiceField;
use Adeliom\SyliusHappyCMSPlugin\Admin\Field\FlexibleContentField;
use Adeliom\SyliusHappyCMSPlugin\Admin\Field\SEOField;
use Adeliom\SyliusEasyCrudPlugin\Admin\AbstractAdmin;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\ColumnField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\EnumField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\SlugField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\TabField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\TranslationField;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Action\Action;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Config\Actions;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Config\Crud;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\Field;
use Adeliom\SyliusEasyCrudPlugin\Enum\ColumnSizeEnum;
use Adeliom\SyliusEasyCrudPlugin\Enum\ThreeStateStatusEnum;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class AbstractPageAdmin extends AbstractAdmin implements ServiceSubscriberInterface
{
    public static function getSubscribedServices(): array
    {
        return [];
    }

    public static function getName(): string
    {
        return 'happy_cms_page_admin';
    }

    public static function getDefaultSortColumn(): string
    {
        return 'name';
    }

    public function configureActions(string $pageName): Actions
    {
        $actions = parent::configureActions($pageName);
        $contentAction = Action::new('content', 'happy_cms.page.admin.action.manage_content', 'flag outline')
            ->addSubAction(
                Action::new('fr_FR', 'fr_FR', 'flag outline')
                    ->linkToRoute('happy_cms_admin_page_update', [
                        'context' => 'flexible_content:fr_FR',
                    ])
            )
            ->addSubAction(
                Action::new('de_DE', 'de_DE', 'flag outline')
                    ->linkToRoute('happy_cms_admin_page_update', [
                        'context' => 'flexible_content:de_DE',
                    ])
            );

        //$actions->addItemAction(Crud::PAGE_INDEX, $contentAction);
        $actions->addItemAction(Crud::PAGE_DETAIL, $contentAction);
        $actions->addItemAction(Crud::PAGE_EDIT, $contentAction);

        return $actions;
    }

    public function configureFields(string $pageName, ?string $context = null): iterable
    {
        if (is_null($context)) {

            yield TabField::new('Page', 'happy_cms.page.admin.tab.page');

            yield ResourceAutocompleteChoiceField::new('parent', 'happy_cms.page.admin.field.parent')
                ->setMultiple(false)
                ->setResource('happy_cms.page');

            yield Field::new('name', 'happy_cms.page.admin.field.name')
                ->setSortablePath('translations.name')
                ->onlyOnIndex();

            yield Field::new('slug', 'happy_cms.page.admin.field.slug')
                ->setSortablePath('translations.slug')
                ->onlyOnIndex();

            yield ColumnField::new('happy_cms.page.admin.panel.metadatas')
                ->setSize(ColumnSizeEnum::WIDE_8_OF_16);

            yield TranslationField::new('translations', 'happy_cms.page.admin.field.translations')
                ->addField(
                    Field::new('name')
                        ->setFormTypeOption('constraints', [
                            //new NotBlank(),
                        ])
                )
                ->addField(
                    SlugField::new('slug')
                        ->setLabel('happy_cms.page.admin.field.slug')
                        ->setFormTypeOption('constraints', [
                            //new NotBlank(),
                        ])
                )
                ->hideOnIndex();

            yield ColumnField::new('happy_cms.page.admin.panel.publication')
                ->setSize(ColumnSizeEnum::WIDE_8_OF_16);

            yield EnumField::new('publishState', 'happy_cms.page.admin.field.state')
                ->setEnum(ThreeStateStatusEnum::class)
                ->hideOnIndex()
                ->renderExpanded(true);

            yield TabField::new('seo', 'happy_cms.page.admin.tab.seo');

            yield TranslationField::new('seoTranslations', 'happy_cms.page.admin.field.seo.translations')
                ->addField(
                    SEOField::new('seo', 'happy_cms.page.admin.field.seo')
                        ->setDisabled(false)
                        ->setRequired(true)
                        ->setFormTypeOption('constraints', [
//                        new Length(['min' => 1])
                        ])
                )
                ->hideOnIndex();

        } elseif (str_starts_with($context, 'flexible_content:')) {

        $locale = str_replace( 'flexible_content:', '', $context);
    //            yield SortableCollectionField::new('content')
    //                ->setEntryType(DataType::class)
    //                ->hideOnIndex();
            yield TranslationField::new('translations')
                ->restrictToLocales([
                    $locale
                ])
                ->addField(
                    FlexibleContentField::new('content')
                        ->hideOnIndex()
                )
                ->hideOnIndex();

        }
    }
}
