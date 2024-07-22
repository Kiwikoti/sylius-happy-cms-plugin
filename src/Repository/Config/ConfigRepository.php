<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Repository\Config;

use Adeliom\SyliusEasyCrudPlugin\Repository\TranslationRepositoryInterface;
use Adeliom\SyliusEasyCrudPlugin\Traits\TranslationRepositoryTrait;
use Adeliom\SyliusHappyCMSPlugin\Entity\Config\ConfigInterface;
use Doctrine\ORM\NonUniqueResultException;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @implements RepositoryInterface<ConfigInterface>
 */
class ConfigRepository extends EntityRepository implements RepositoryInterface, TranslationRepositoryInterface
{
    use TranslationRepositoryTrait;

    /**
     * @throws NonUniqueResultException
     */
    public function getByKey(string $key): ?ConfigInterface
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.key = :key')
            ->setParameter('key', $key);

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }
}
