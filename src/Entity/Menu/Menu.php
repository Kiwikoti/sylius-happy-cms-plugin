<?php

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Menu;

use Adeliom\SyliusHappyCMSPlugin\Repository\Menu\MenuRepository;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityStatusTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityTimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('code')]
#[ORM\HasLifecycleCallbacks]
#[ORM\MappedSuperclass(repositoryClass: MenuRepository::class)]
class Menu implements ResourceInterface, \Stringable
{
    use EntityIdTrait;
    use EntityTimestampableTrait {
        EntityTimestampableTrait::__construct as private timestampableConstruct;
    }
    use EntityStatusTrait;
    public $menuItems;

    /**
     * @var ArrayCollection<MenuItem>|null
     */
    protected $items;

    /**
     * @var string
     **/
    #[ORM\Column(name: 'code', type: \Doctrine\DBAL\Types\Types::STRING, length: 30)]
    protected ?string $code = null;

    #[ORM\Column(name: 'name', type: \Doctrine\DBAL\Types\Types::STRING, length: 255, nullable: true)]
    protected ?string $name = null;

    /**
     * @var MenuItem|null
     */
    protected $rootItem;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->timestampableConstruct();
        $this->items = new ArrayCollection();
    }

    /**
     * Set name.
     */
    public function setName(?string $name)
    {
        $this->name = $name;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get menuItems.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @return MenuItem[]|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function addItem(MenuItem $item): void
    {
        $this->items->add($item);
        if ($item->getMenu() !== $this) {
            $item->setMenu($this);
        }
    }

    public function removeItem(MenuItem $item): void
    {
        $this->items->removeElement($item);
        $item->setMenu(null);
    }

    #[ORM\PreRemove]
    public function onRemove(): void
    {
        $this->setStatus(false);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getRootItem(): ?MenuItem
    {
        return $this->rootItem;
    }

    public function setRootItem(?MenuItem $rootItem): void
    {
        $this->rootItem = $rootItem;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
