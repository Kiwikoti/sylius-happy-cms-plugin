<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Repository\Menu;

use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\Menu;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class MenuRepository extends EntityRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;

    protected bool $cacheEnabled = false;

    protected int $cacheTtl;

    public function setConfig(array $cacheConfig): void
    {
        $this->cacheEnabled = (bool) $cacheConfig['enabled'];
        $this->cacheTtl = (int) $cacheConfig['ttl'];
    }

    public function getPublishedQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('menu')
            ->where('menu.status = :status')
        ;

        $qb->setParameter('status', true);

        return $qb;
    }

    /**
     * @return Menu[]
     */
    public function getPublished(): array
    {
        $qb = $this->getPublishedQuery();

        if ($this->cacheEnabled) {
            $qb = $qb->getQuery()->enableResultCache($this->cacheTtl);
        } else {
            $qb = $qb->getQuery()->disableResultCache();
        }

        return $qb->getResult();
    }
}
