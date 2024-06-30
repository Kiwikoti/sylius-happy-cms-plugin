<?php

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Page;

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
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\PreRemove;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[HasLifecycleCallbacks]
#[MappedSuperclass]
class PageTranslation extends AbstractTranslation implements ResourceInterface, \Stringable, SeoInterface
{
    use EntityIdTrait;

    use EntityNameSlugTrait;

    use EntitySeoTrait {
        EntitySeoTrait::__construct as private SEOConstruct;
    }

    /**
     * @var array|null
     */
    #[Groups('main')]
    #[Column(name: 'content', type: Types::JSON, nullable: true)]
    #[Assert\Type('array')]
    protected $content = [];

    public function __construct()
    {
        $this->SEOConstruct();
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

    public function getTree(string $separator = '/', bool $name = false): string
    {
        $tree = '';

        $current = $this;
        do {
            $slug = method_exists($current, 'getPageSlug') ? $current->getPageSlug() : $current->getSlug();
            $tree = $name ? $current->getName() . $separator . $tree : $slug . $separator . $tree;
            if (!is_null($current->getTranslatable())) {
                $current = $current->getTranslatable()->getParent() ?? null;
            } else {
                $current = null;
            }
        } while ($current);

        return trim($tree, $separator);
    }

    public function getTreeDisplay(): string
    {
        $tree = ' ' . $this->getName();

        $current = $this;
        do {
            $tree = '―' . $tree;
            if (!is_null($current->getTranslatable())) {
                $current = $current->getTranslatable()->getParent() ?? null;
            } else {
                $current = null;
            }
        } while ($current);

        return mb_substr($tree, 1);
    }

    #[PrePersist]
    #[PreUpdate]
    public function setSeoTitle(PrePersistEventArgs|PreUpdateEventArgs $event): void
    {
        if (empty($this->getName())) {
            // $this->setName('No name');
        }
        if (empty($this->getSEO()->title)) {
            $this->getSEO()->title = $this->getName();
        }
    }

    #[PreRemove]
    public function onRemove(PreRemoveEventArgs $event): void
    {
        /** @var \Adeliom\SyliusHappyCMSPlugin\Entity\Page\Page $page */
        $page = $this->getTranslatable();
        $this->setName($this->getName() . '-' . $page->getId() . '-deleted');
        $this->setSlug($this->getSlug() . '-' . $page->getId() . '-deleted');
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
