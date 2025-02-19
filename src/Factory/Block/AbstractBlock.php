<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Factory\Block;

use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Config\Asset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractBlock extends AbstractType implements BlockTypeInterface
{
    protected ?FormBuilderInterface $tempBuilder = null;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        protected FormFactoryInterface $formFactory,
    ) {
    }

    public function getManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('block_type', HiddenType::class, ['data' => $this::class])
            ->add('block_published', HiddenType::class)
            ->add('position', HiddenType::class)
        ;
        $this->buildBlock($builder, $options);
    }

    /**
     * @param array<string, mixed> $options
     */
    abstract public function buildBlock(FormBuilderInterface $builder, array $options): void;

    private function tempBuilder(): void
    {
        if (null === $this->tempBuilder) {
            $this->tempBuilder = $this->formFactory
                ->createNamedBuilder(
                    'fake_builder',
                    static::class,
                    null,
                    [],
                );
            $this->buildBlock($this->tempBuilder, []);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attr = [];
        $attr['block-title'] = $this->getName();
        $attr['block-icon'] = is_iterable($this->getIcon()) ? $this->getIcon()[0] : $this->getIcon();
        $view->vars['attr'] = $attr;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'cascade_validation' => true,
        ]);
    }

    /**
     * Declare here the assets that make front working as expected
     *
     * @return array{js: array<string|Asset>|null, css: array<string|Asset>|null, webpack: array<string|Asset>|null}
     */
    public function configureAssets(): array
    {
        return [
            'js' => [],
            'css' => [],
            'webpack' => [],
        ];
    }

    /**
     * Declare here the assets that make back-office working as expected
     *
     * @return array{js: array<string|Asset>|null, css: array<string|Asset>|null, webpack: array<string|Asset>|null}
     */
    public function configureAdminAssets(): array
    {
        $this->tempBuilder();
        /**
         * @var array{js: array<string|Asset>|null, css: array<string|Asset>|null, webpack: array<string|Asset>|null} $adminAssets
         */
        $adminAssets = [
            'js' => [],
            'css' => [],
            'webpack' => [],
        ];
        foreach ($this->tempBuilder->getForm() as $child) {
            $formTypeClass = get_class($child->getConfig()->getType()->getInnerType());
            if (method_exists($formTypeClass, 'configureAdminAssets')) {
                $assets = call_user_func([$formTypeClass, 'configureAdminAssets']);
                if (is_array($assets)) {
                    $adminAssets = array_merge_recursive($adminAssets, $assets);
                }
            }
        }

        return $adminAssets;
    }

    /**
     * Declare here the form themes path that make back-office form display as expected
     *
     * @return string[]
     */
    public function configureAdminFormThemes(): array
    {
        $this->tempBuilder();
        $adminFormThemes = [];
        foreach ($this->tempBuilder->getForm() as $child) {
            $formTypeClass = get_class($child->getConfig()->getType()->getInnerType());
            if (method_exists($formTypeClass, 'configureAdminFormThemes')) {
                $formThemes = call_user_func([$formTypeClass, 'configureAdminFormThemes']);
                if (is_array($formThemes)) {
                    $adminFormThemes = array_merge($adminFormThemes, $formThemes);
                }
            }
        }

        return $adminFormThemes;
    }

    /**
     * @return string[]
     */
    public static function researchableProperties(): array
    {
        return [];
    }

    public function getTab(): string
    {
        return $this->translator->trans('sylius_happy_cms.editor.blocks');
    }

    public function getPosition(): int
    {
        return 100;
    }

    public function supports(string $objectClass, ?object $instance = null): bool
    {
        return true;
    }
}
