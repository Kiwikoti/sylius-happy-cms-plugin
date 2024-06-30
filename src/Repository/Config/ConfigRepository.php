<?php

namespace Adeliom\SyliusHappyCMSPlugin\Repository\Config;

use Adeliom\SyliusEasyCrudPlugin\Repository\TranslationRepositoryInterface;
use Adeliom\SyliusEasyCrudPlugin\Traits\TranslationRepositoryTrait;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ConfigRepository extends EntityRepository implements RepositoryInterface, TranslationRepositoryInterface
{
    use TranslationRepositoryTrait;
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByKey($key)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.key = :key')
            ->setParameter('key', $key);

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }
}
