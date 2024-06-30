<?php

namespace Adeliom\SyliusHappyCMSPlugin\Repository\Menu;

use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\Menu;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuItem;
use Adeliom\SyliusEasyCrudPlugin\Enum\ThreeStateStatusEnum;
use Adeliom\SyliusEasyCrudPlugin\Repository\TranslationRepositoryInterface;
use Adeliom\SyliusEasyCrudPlugin\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class MenuItemRepository extends NestedTreeRepository implements ServiceEntityRepositoryInterface, RepositoryInterface, TranslationRepositoryInterface
{
    use ResourceRepositoryTrait;
    use TranslationRepositoryTrait;

    protected bool $cacheEnabled = false;

    protected int $cacheTtl;

    public function setConfig(array $cacheConfig): void
    {
        $this->cacheEnabled = (bool) $cacheConfig['enabled'];
        $this->cacheTtl = (int) $cacheConfig['ttl'];
    }

    public function getPublishedQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('menuitem')
            ->where('menuitem.state = :state')
            ->andWhere('menuitem.publishDate < :publishDate')
        ;

        $orModule = $qb->expr()->orx();
        $orModule->add($qb->expr()->gt('menuitem.unpublishDate', ':unpublishDate'));
        $orModule->add($qb->expr()->isNull('menuitem.unpublishDate'));

        $qb->andWhere($orModule);

        $qb->setParameter('state', ThreeStateStatusEnum::PUBLISHED());
        $qb->setParameter('publishDate', new \DateTime());
        $qb->setParameter('unpublishDate', new \DateTime());

        return $qb;
    }

    /**
     * @return array|\Doctrine\ORM\QueryBuilder ($returnQueryBuilder ? QueryBuilder : MenuItem[])
     */
    public function getPublished(bool $returnQueryBuilder = false): array | QueryBuilder
    {
        $qb = $this->getPublishedQuery();
        if ($returnQueryBuilder) {
            return $qb;
        }

        if ($this->cacheEnabled) {
            $qb = $qb->getQuery()->enableResultCache($this->cacheTtl);
        } else {
            $qb = $qb->getQuery()->disableResultCache();
        }

        return $qb->getResult();
    }

    /**
     * @return array|\Doctrine\ORM\QueryBuilder ($returnQueryBuilder ? QueryBuilder : MenuItem[])
     */
    public function getByMenu(Menu $menu, bool $returnQueryBuilder = false): array | QueryBuilder
    {
        $qb = $this->getPublishedQuery();
        $qb->andWhere('menuitem.menu = :menu')
            ->setParameter('menu', $menu)
        ;
        if ($returnQueryBuilder) {
            return $qb;
        }

        if ($this->cacheEnabled) {
            $qb = $qb->getQuery()->enableResultCache($this->cacheTtl);
        } else {
            $qb = $qb->getQuery()->disableResultCache();
        }

        return $qb->getResult();
    }

    public function filterByMenu(int $menuId, string $locale): QueryBuilder
    {
        return $this->createListQueryBuilder($locale)
            ->andWhere('entity.menu = :menu')
            ->setParameter('menu', $menuId)
            ;
    }

    public function findPreviousMenuItem(MenuItem $menuItem): MenuItem | null
    {
        return $this->createQueryBuilder('mi')
            ->andWhere('mi.id != :id')
            ->andWhere('mi.lvl = :level')
            ->andWhere('mi.position < :position')
            ->andWhere('mi.position IS NOT NULL')
            ->setParameter('id', $menuItem->getId())
            ->setParameter('level', $menuItem->getLvl())
            ->setParameter('position', $menuItem->getPosition())
            ->orderBy('mi.id', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult()
            ;
    }

    public function findNextMenuItem(MenuItem $menuItem): MenuItem | null
    {
        return $this->createQueryBuilder('mi')
            ->andWhere('mi.id != :id')
            ->andWhere('mi.lvl = :level')
            ->andWhere('mi.position > :position')
            ->andWhere('mi.position IS NOT NULL')
            ->setParameter('id', $menuItem->getId())
            ->setParameter('level', $menuItem->getLvl())
            ->setParameter('position', $menuItem->getPosition())
            ->orderBy('mi.id', 'ASC')
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult()
            ;
    }
}
