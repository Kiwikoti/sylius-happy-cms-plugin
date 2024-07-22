<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;

if (
isset($namespace) &&
isset($entityClassName) &&
isset($scope) &&
isset($repository)
) {

$mainClassData = [
    'className' => $entityClassName,
    'lowerName' => mb_strtolower(Str::asSnakeCase($entityClassName)),
];
if (isset($relationClassName)) {
    $relationClassData = [
        'className' => $relationClassName,
        'lowerNames' => [
            'singular' => mb_strtolower(Str::asSnakeCase($relationClassName)),
            'plural' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($relationClassName))),
        ],
    ];
}
$scope = mb_strtolower($scope);

?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use App\Entity\HappyCMS\<?= ucfirst($scope) ?>\<?= $mainClassData['className'] ?>;
use Adeliom\SyliusEasyCrudPlugin\Admin\AbstractAdmin;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\EnumField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\FormTypeField;
use Adeliom\SyliusHappyCMSPlugin\Admin\Field\SEOField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\ColumnField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\SlugField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\TabField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\TranslationField;
use Adeliom\SyliusEasyCrudPlugin\Admin\Field\ResourceAutocompleteChoiceField;
use Adeliom\SyliusHappyCMSPlugin\Admin\Field\FlexibleContentField;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\Field;
use Adeliom\SyliusEasyCrudPlugin\Enum\ThreeStateStatusEnum;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Action\Action;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Config\Actions;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Config\Crud;
use Adeliom\SyliusEasyCrudPlugin\Enum\ColumnSizeEnum;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Length;

final class <?= $mainClassData['className'] ?>Admin extends AbstractAdmin implements ServiceSubscriberInterface
{
    public static function getSubscribedServices(): array
    {
        return [];
    }

    public static function getName(): string
    {
        return 'sylius_happy_cms_admin_<?= $scope ?>_<?= $mainClassData['lowerName'] ?>';
    }

    public static function getEntityFqcn(): string
    {
        return <?= $mainClassData['className'] ?>::class;
    }

    public static function getDefaultSortColumn(): string
    {
        return 'name';
    }

<?php if (isset($withFlexibleContent) && $withFlexibleContent) { ?>
    public function configureActions(string $pageName): Actions
    {
        $actions = parent::configureActions($pageName);
        $contentAction = Action::new('content', 'sylius_happy_cms.page.admin.action.manage_content', 'flag outline')
            ->addSubAction(
                Action::new('fr_FR', 'fr_FR', 'flag outline')
                    ->linkToRoute('happy_cms_admin_page_update', [
                        'context' => 'flexible_content:fr_FR',
                    ])
            )
            ->addSubAction(
                Action::new('de_DE', 'de_DE', 'flag outline')
                    ->linkToRoute('sylius_happy_cms_admin_page_update', [
                        'context' => 'flexible_content:de_DE',
                    ])
            );

        //$actions->addItemAction(Crud::PAGE_INDEX, $contentAction);
        $actions->addItemAction(Crud::PAGE_DETAIL, $contentAction);
        $actions->addItemAction(Crud::PAGE_EDIT, $contentAction);

        return $actions;
    }
<?php } ?>

    public function configureFields(string $pageName, ?string $context = null): iterable
    {
        if (is_null($context)) {

            yield TabField::new('<?= $mainClassData['lowerName'] ?>', 'sylius_happy_cms.<?= $scope ?>.admin.tab.<?= $mainClassData['lowerName'] ?>');

            yield ColumnField::new('sylius_happy_cms.<?= $scope ?>.admin.panel.metadatas')
                ->setSize(ColumnSizeEnum::WIDE_8_OF_16);

            yield Field::new('name')
                ->setLabel('<?= $mainClassData['className'] ?>')
                ->setSortablePath('translations.name')
                ->onlyOnIndex();

        <?php if (isset($relationClassData)) { ?>
            yield ResourceAutocompleteChoiceField::new('<?= $relationClassData['lowerNames']['plural'] ?>', '<?= $relationClassData['lowerNames']['plural'] ?>')
                ->setResource('sylius_happy_cms.<?= $scope ?>_<?= $relationClassData['lowerNames']['singular'] ?>')
                ->setMultiple()
                ->setChoiceValue('id')
                ->setChoiceName('name')
                ->setRepositoryMethod('findByPhrase')
                ->setRemoteCriteriaName('phrase')
                ->setRepositoryArguments([
                    'phrase' => '$phrase',
                    'locale' => "expr:service('sylius.context.locale').getLocaleCode()",
                    'limit' => 10,
                    'fieldName' => 'name',
                ]);
        <?php } ?>

            yield TranslationField::new('translations')
                ->addField(
                    Field::new('name')
                        ->setDisabled(false)
                        ->setRequired(true)
                        ->setFormTypeOption('constraints', [
                            new Length(['min' => 1])
                        ])
                )
                ->addField(
                    SlugField::new('slug')
                        ->setRequired(true)
                        ->setFormTypeOption('constraints', [
                            new Length(['min' => 1])
                        ])
                )
        <?php
        if (!empty($extraFields)) {
            foreach ($extraFields as $fieldData) {
                ?>
                ->addField(
                    SlugField::new('<?= $fieldData['name'] ?>', '<?= $fieldData['name'] ?>')
                        ->setRequired(true)
                        ->setFormTypeOption('constraints', [
                            new Length(['min' => 1])
                        ])
                )
                <?php
            }
        }
?>
                ->hideOnIndex();

            yield ColumnField::new('sylius_happy_cms.<?= $scope ?>.admin.panel.publication')
                ->setSize(ColumnSizeEnum::WIDE_8_OF_16);

            yield FormTypeField::new('publishDate', 'Date de publication', DateTimeType::class)
                ->setFormTypeOption('widget', 'single_text')
                ->setFormTypeOption('html5', 'true')
                ->hideOnIndex();

            yield FormTypeField::new('unpublishDate')
                ->setFormType(DateTimeType::class)
                ->setFormTypeOption('widget', 'single_text')
                ->setFormTypeOption('html5', 'true')
                ->hideOnIndex();

            yield EnumField::new('publishState')
                ->setEnum(ThreeStateStatusEnum::class)
                ->renderExpanded()
                ->hideOnIndex();

            yield TabField::new('seo', 'sylius_happy_cms.page.admin.tab.seo');

            yield TranslationField::new('seoTranslations', 'sylius_happy_cms.page.admin.field.seo.translations')
                ->addField(
                    SEOField::new('seo', 'sylius_happy_cms.page.admin.field.seo')
                        ->setDisabled(false)
                        ->setRequired(true)
                        ->setFormTypeOption('constraints', [
            //                        new Length(['min' => 1])
                        ])
                )
                ->hideOnIndex();
        }
<?php if (isset($withFlexibleContent) && $withFlexibleContent) { ?>
        elseif (str_starts_with($context, 'flexible_content:')) {
            $locale = str_replace( 'flexible_content:', '', $context);
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
<?php } ?>
    }
}

<?php } ?>
