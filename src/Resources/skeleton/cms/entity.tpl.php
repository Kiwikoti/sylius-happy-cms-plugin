<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;

$mainClassData = [
    'className' => $entityClassName,
    'lowerNames' => [
        'singular' => mb_strtolower(Str::asSnakeCase($entityClassName)),
        'plural' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($entityClassName))),
    ]
];
$relationClassData = [
    'className' => $relationClassName,
    'lowerNames' => [
        'singular' => mb_strtolower(Str::asSnakeCase($relationClassName)),
        'plural' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($relationClassName))),
    ]
];
$scope = mb_strtolower($scope);
?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use <?= $repository['FQCN'] ?>;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityPublishableTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityStatusTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityTimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use JMS\Serializer\Annotation as Serializer;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
<?php if (true === $options['hasRouting']) { ?>
use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityRouteTrait;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as OrmRoute;
<?php } ?>
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'happy_cms_<?= $scope ?>__<?= $mainClassData['lowerNames']['singular'] ?>')]
#[ORM\Index(columns: ['publishState'], name: '<?= $scope ?>__<?= $mainClassData['lowerNames']['singular'] ?>_indexes')]
#[ORM\Entity(repositoryClass: <?= $repository['name'] ?>::class)]
#[Serializer\ExclusionPolicy('ALL')]
class <?= $mainClassData['className'] ?> implements ResourceInterface, TranslatableInterface<?= $options['hasRouting'] ? ', CmsRoutableInterface' : '' ?>
{
    use TranslatableTrait {
        TranslatableTrait::__construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }
    use EntityTimestampableTrait {
        EntityTimestampableTrait::__construct as private timestampableConstruct;
    }
    use EntityPublishableTrait {
        EntityPublishableTrait::__construct as private publishableConstruct;
    }
<?php if (true === $options['hasRouting']) { ?>
    use EntityRouteTrait {
        EntityRouteTrait::__construct as private entityRouteConstruct;
    }
<?php } ?>

    use EntityIdTrait;

    #[ORM\ManyToMany(
        targetEntity: <?= $relationClassData['className'] ?>::class,
        <?= $options['isOwningSide'] ? 'inversedBy:' : 'mappedBy:' ?> '<?= $mainClassData['lowerNames']['plural'] ?>'
    )]
<?php if (true === $options['isOwningSide']) { ?>
    #[ORM\JoinTable(name: 'happy_cms_<?= $scope ?>__<?= $relationClassData['lowerNames']['singular'] ?>_<?= $mainClassData['lowerNames']['singular'] ?>')]
<?php } ?>
    protected Collection $<?= $relationClassData['lowerNames']['plural'] ?>;

<?php if ( true === $options['hasRouting']) { ?>
    #[ORM\ManyToMany(targetEntity: OrmRoute::class, cascade: ["persist", "remove"])]
    #[ORM\JoinTable('happy_cms_<?= $scope ?>__<?= $mainClassData['lowerNames']['singular'] ?>_route')]
    protected Collection $routes;
<?php } ?>

    #[ORM\Column(name: 'css', type: Types::TEXT, nullable: true)]
    #[Assert\Type('string')]
    protected ?string $css = null;

    #[ORM\Column(name: 'js', type: Types::TEXT, nullable: true)]
    #[Assert\Type('string')]
    protected ?string $js = null;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->publishableConstruct();
        $this->timestampableConstruct();
    <?php if ( true === $options['hasRouting']) { ?>
        $this->entityRouteConstruct();
    <?php } ?>

        $this-><?= $relationClassData['lowerNames']['plural'] ?> = new ArrayCollection();
    }

    protected function createTranslation(): \Sylius\Component\Resource\Model\TranslationInterface
    {
        return new <?= $mainClassData['className'] ?>Translation();
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function getTranslation(?string $locale = null): \Sylius\Component\Resource\Model\TranslationInterface
    {
        return $this->doGetTranslation($locale);
    }

    public static function getTranslationClass(): string
    {
        return <?= $mainClassData['className'] ?>Translation::class;
    }

    #[Serializer\Expose]
    #[Serializer\VirtualProperty]
    #[Serializer\SerializedName('name')]
    #[Serializer\Type('string')]
    #[Serializer\Groups(['Detailed', 'Default', 'Autocomplete'])]
    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getSeoTranslations(): Collection
    {
        return $this->translations;
    }

    public function getSlugTranslations(): Collection
    {
        return $this->translations;
    }

    public function get<?= ucfirst($relationClassData['lowerNames']['plural']) ?>(): ?Collection
    {
        return $this-><?= $relationClassData['lowerNames']['plural'] ?>;
    }

    public function add<?= $relationClassData['className'] ?>(?<?= $relationClassData['className'] ?> $<?= $relationClassData['lowerNames']['singular'] ?>): void
    {
        $this-><?= $relationClassData['lowerNames']['plural'] ?>->add($<?= $relationClassData['lowerNames']['singular'] ?>);
    <?php if (true === $options['isOwningSide']) { ?>
        if (!$<?= $relationClassData['lowerNames']['singular'] ?>->get<?= ucfirst($mainClassData['lowerNames']['plural']) ?>()->contains($this)) {
            $<?= $relationClassData['lowerNames']['singular'] ?>->add<?= ucfirst($mainClassData['lowerNames']['singular']) ?>($this);
        }
    <?php } ?>
    }

    public function remove<?= $relationClassData['className'] ?>(?<?= $relationClassData['className'] ?> $<?= $relationClassData['lowerNames']['singular'] ?>): void
    {
        $this-><?= $relationClassData['lowerNames']['plural'] ?>->removeElement($<?= $relationClassData['lowerNames']['singular'] ?>);
    <?php if (true === $options['isOwningSide']) { ?>
        $<?= $relationClassData['lowerNames']['singular'] ?>->add<?= ucfirst($mainClassData['lowerNames']['singular']) ?>(null);
    <?php } ?>
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(string $css): void
    {
        $this->css = $css;
    }

    public function getJs(): ?string
    {
        return $this->js;
    }

    public function setJs(string $js): void
    {
        $this->js = $js;
    }

<?php if ( true === $options['hasRouting']) { ?>
    public function getRouteUnikName(): string
    {
        return 'happy_cms_<?= $scope ?>_' . $this->getId();
    }

    public function getRouteStaticPrefix(TranslationInterface $translation, bool $isPreview): string
    {
        $slug = (method_exists($translation, 'getSlug') ? $translation->getSlug() : '');
        $locale = $translation->getLocale();
        return sprintf('/%s/<?= $scope ?>/%s%s', $locale, $slug, ($isPreview ? '-preview': ''));
    }
<?php } ?>

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
