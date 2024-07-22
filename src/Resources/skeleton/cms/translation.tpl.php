<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;

if (
    isset($namespace, $entityClassName, $scope)
) {
    $mainClassData = [
        'className' => $entityClassName,
        'lowerName' => mb_strtolower(Str::asSnakeCase($entityClassName)),
    ];
    $scope = mb_strtolower($scope);
    ?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap\SeoInterface;
use Adeliom\SyliusHappyCMSPlugin\Traits\Seo\EntitySeoTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityNameSlugTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: '<?= $scope ?>_<?= $mainClassData['lowerName'] ?>__slug_locale', columns: ['slug', 'locale'])]
#[ORM\Table(name: 'happy_cms_<?= $scope ?>__<?= $mainClassData['lowerName'] ?>_translation')]
class <?= $mainClassData['className'] ?>Translation extends AbstractTranslation implements ResourceInterface, \Stringable, SeoInterface
{
    use EntityIdTrait;
    use EntityNameSlugTrait;
    use EntitySeoTrait {
        EntitySeoTrait::__construct as private SEOConstruct;
    }

<?php if (isset($withFlexibleContent) && $withFlexibleContent) { ?>
    /**
    * @var array|null
    */
    #[Groups('main')]
    #[ORM\Column(name: 'content', type: Types::JSON, nullable: true)]
    #[Assert\Type('array')]
    protected $content = [];
<?php }?>

    public function __construct()
    {
        $this->SEOConstruct();
    }
<?php
if (!empty($extraFields)) {
    foreach ($extraFields as $fieldData) {
        ?>
    #[ORM\Column(type: Types::<?= mb_strtoupper($fieldData['columnType']) ?>)]
    protected ?<?= $fieldData['phpType'] ?> $<?= $fieldData['name'] ?> = null;

    public function get<?= ucfirst($fieldData['name']) ?>(): ?<?= $fieldData['phpType'] ?>
    {
        return $this-><?= $fieldData['name'] ?>;
    }

    public function set<?= ucfirst($fieldData['name']) ?>(?<?= $fieldData['phpType'] ?> $<?= $fieldData['name'] ?>): void
    {
        $this-><?= $fieldData['name'] ?> = $<?= $fieldData['name'] ?>;
    }
<?php
    }
}
    ?>

<?php if (isset($withFlexibleContent) && $withFlexibleContent) { ?>
    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

<?php } ?>
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setSeoTitle(PrePersistEventArgs|PreUpdateEventArgs $event): void
    {
        if (empty($this->getName())) {
            // $this->setName('No name');
        }
        if (empty($this->getSEO()->title)) {
            $this->getSEO()->title = $this->getName();
        }
    }

    #[ORM\PreRemove]
    public function onRemove(PreRemoveEventArgs $event): void
    {
        $page = $this->getTranslatable();
        $this->setName($this->getName() . '-' . $page->getId() . '-deleted');
        $this->setSlug($this->getSlug() . '-' . $page->getId() . '-deleted');
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}

<?php } ?>
