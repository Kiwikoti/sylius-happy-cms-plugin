<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Media;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\MappedSuperclass]
#[ORM\Entity]
#[ORM\Table(name: 'sylius_happy_cms__folder')]
class Folder implements FolderInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    protected ?int $id = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 100)]
    protected ?string $slug = null;

    protected ?FolderInterface $parent = null;

    /** @var Collection<int, FolderInterface> */
    protected Collection $children;

    /** @var Collection<int, MediaInterface> */
    protected Collection $medias;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;

        if (!$this->slug) {
            $this->slug = (new AsciiSlugger())->slug(strtolower($this->name))->toString();
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getParent(): ?FolderInterface
    {
        return $this->parent;
    }

    /** @return  Collection<int, FolderInterface> */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(FolderInterface $child): void
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    /** @return  Collection<int, MediaInterface> */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(MediaInterface $media): void
    {
        $this->medias[] = $media;
        $media->setFolder($this);
    }

    public function setParent(?FolderInterface $parent = null): void
    {
        $this->parent = $parent;
    }

    public function getPath(string $separator = '/'): string
    {
        $tree = '';
        $current = $this;
        do {
            $tree = $current->getSlug() . $separator . $tree;
            $current = $current->getParent();
        } while ($current);

        return trim($tree, $separator);
    }
}
