<?php

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Menu;

use Adeliom\SyliusHappyCMSPlugin\Repository\Menu\MenuItemRepository;
use Adeliom\SyliusEasyCrudPlugin\Enum\ThreeStateStatusEnum;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityPublishableTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityThreeStateStatusTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityTimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

#[ORM\HasLifecycleCallbacks]
#[ORM\MappedSuperclass(repositoryClass: MenuItemRepository::class)]
#[Gedmo\Tree(type: 'nested')]
class MenuItem implements ResourceInterface, TranslatableInterface, \Stringable
{
    use EntityIdTrait;
    use EntityTimestampableTrait {
        EntityTimestampableTrait::__construct as private timestampableConstruct;
    }
    use EntityPublishableTrait {
        EntityPublishableTrait::__construct as private publishableConstruct;
    }

    use TranslatableTrait {
        TranslatableTrait::__construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    #[ORM\Column(name: 'lft', type: \Doctrine\DBAL\Types\Types::INTEGER)]
    #[Gedmo\TreeLeft]
    protected ?int $lft = null;

    #[ORM\Column(name: 'lvl', type: \Doctrine\DBAL\Types\Types::INTEGER)]
    #[Gedmo\TreeLevel]
    protected ?int $lvl = null;

    #[ORM\Column(name: 'rgt', type: \Doctrine\DBAL\Types\Types::INTEGER)]
    #[Gedmo\TreeRight]
    protected ?int $rgt = null;

    #[ORM\Column(name: 'root', type: \Doctrine\DBAL\Types\Types::INTEGER, nullable: true)]
    #[Gedmo\TreeRoot]
    protected ?int $root = null;

    /**
     * @var Menu|null
     */
    protected $menu;

    /**
     * @var string
     */

    #[ORM\Column(name: 'class_attribute', type: \Doctrine\DBAL\Types\Types::STRING, length: 255, nullable: true)]
    protected ?string $classAttribute = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'position', type: \Doctrine\DBAL\Types\Types::SMALLINT, options: ['unsigned' => true], nullable: true)]
    protected ?int $position = null;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'target', type: \Doctrine\DBAL\Types\Types::BOOLEAN, nullable: true, options: ['default' => false])]
    protected ?bool $target = null;

    /**
     * @var MenuItem|null
     */
    #[ORM\JoinColumn(name: 'parent_id', onDelete: 'CASCADE')]
    #[Gedmo\TreeParent]
    protected ?MenuItem $parent = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<MenuItem>
     */
    #[ORM\OrderBy(['lft' => 'ASC'])]
    protected \Doctrine\Common\Collections\Collection $children;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->timestampableConstruct();
        $this->publishableConstruct();
        $this->children = new ArrayCollection();
    }

    protected function createTranslation(): TranslationInterface
    {
        return new MenuItemTranslation();
    }

    public function getTranslation(?string $locale = null): MenuItemTranslation
    {
        /** @var MenuItemTranslation $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    public static function getTranslationClass(): string
    {
        return MenuItemTranslation::class;
    }

    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    public function setLft(mixed $lft): void
    {
        $this->lft = $lft;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    public function setLvl(mixed $lvl): void
    {
        $this->lvl = $lvl;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    public function setRgt(mixed $rgt): void
    {
        $this->rgt = $rgt;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot(mixed $root): void
    {
        $this->root = $root;
    }

    public function getSortableData($name)
    {
        return $this->{$name};
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function getClassAttribute(): ?string
    {
        return $this->classAttribute;
    }

    public function setClassAttribute(?string $classAttribute): void
    {
        $this->classAttribute = $classAttribute;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function isTarget(): ?bool
    {
        return $this->target;
    }

    public function setTarget(?bool $target): void
    {
        $this->target = $target;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return MenuItem|null
     */
    public function getParent(): ?MenuItem
    {
        return $this->parent;
    }

    public function setParent(?MenuItem $parent)
    {
        $this->parent = $parent;

        if (!is_null($parent)) {
            $parent->addChild($this);
        }
    }

    /**
     * Add child.
     */
    public function addChild(MenuItem $child)
    {
        $this->children[] = $child;
    }

    /**
     * Remove child.
     */
    public function removeChild(MenuItem $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Set children.
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get only published children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublishedChildren()
    {
        return $this->children->filter(static fn (MenuItem $child) => $child->getPublishState() == ThreeStateStatusEnum::PUBLISHED
            ());
    }

    #[ORM\PreRemove]
    public function onRemove(): void
    {
        $this->setPublishState(ThreeStateStatusEnum::UNPUBLISHED());
    }

    /**
     * Has child.
     */
    public function hasChild()
    {
        return count($this->children) > 0;
    }

    /**
     * Has parent.
     */
    public function hasParent(): bool
    {
        return !is_null($this->parent);
    }

    public function getParents($parents = [], $parent = null)
    {
        if (empty($parent)) {
            $parents[] = (string) $this;
            $parent = $this;
        }

        if (!empty($parent->getParent())) {
            $parentParent = $parent->getParent();
            $parents[] = (string) $parentParent;
            $parents = $this->getParents($parents, $parentParent);
        }

        return $parents;
    }

    public function getFlattenParents(): string
    {
        return implode(' / ', array_reverse($this->getParents()));
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
