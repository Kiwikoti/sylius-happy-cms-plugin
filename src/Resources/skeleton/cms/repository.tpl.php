<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;

$mainClassData = [
    'className' => $entityClassName,
    'lowerNames' => [
        'singular' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($entityClassName))),
        'plural' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($entityClassName))),
    ],
];
$relationClassData = [
    'className' => $relationClassName,
    'lowerNames' => [
        'singular' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($relationClassName))),
        'plural' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($relationClassName))),
    ],
];
$scope = mb_strtolower($scope);
?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use Adeliom\SyliusEasyCrudPlugin\Enum\ThreeStateStatusEnum;
use Adeliom\SyliusEasyCrudPlugin\Repository\TranslationRepositoryInterface;
use Adeliom\SyliusEasyCrudPlugin\Traits\TranslationRepositoryTrait;
use App\Entity\HappyCMS\<?= ucfirst($scope) ?>\<?= $relationClassData['className'] ?>;
use App\Entity\HappyCMS\<?= ucfirst($scope) ?>\<?= $mainClassData['className'] ?>;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository&lt;<?= $mainClassData['className'] ?>&gt;
 *
 * @method <?= $mainClassData['className'] ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $mainClassData['className'] ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $mainClassData['className'] ?>[]    findAll()
 * @method <?= $mainClassData['className'] ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $mainClassData['className'] ?>Repository extends EntityRepository implements TranslationRepositoryInterface
{
    use TranslationRepositoryTrait;

    private const ALIAS = '<?= $scope ?>__<?= $mainClassData['lowerNames']['singular'] ?>';

    protected bool $cacheEnabled = false;
    protected int $cacheTtl;

    public function setConfig(array $cacheConfig): void
    {
        $this->cacheEnabled = $cacheConfig['enabled'];
        $this->cacheTtl = $cacheConfig['ttl'];
    }

    public function getPublishedQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder(self::ALIAS)
            ->where(self::ALIAS.'.publishState = :publishState')
            ->andWhere(self::ALIAS.'.publishDate < :publishDate')
        ;

        $orModule = $qb->expr()->orx();
        $orModule->add($qb->expr()->gt(self::ALIAS.'.unpublishDate', ':unpublishDate'));
        $orModule->add($qb->expr()->isNull(self::ALIAS.'.unpublishDate'));

        $qb->andWhere($orModule);

        $qb->setParameter('publishState', ThreeStateStatusEnum::PUBLISHED());
        $qb->setParameter('publishDate', new \DateTime());
        $qb->setParameter('unpublishDate', new \DateTime());

        return $qb;
    }

    public function getPublished(bool $returnQueryBuilder = false): QueryBuilder|array
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
     * @throws NonUniqueResultException
     */
    public function getBySlug(
        string $slug,
        ?<?= $relationClassData['className'] ?> $<?= $relationClassData['lowerNames']['singular'] ?>,
        bool $returnQueryBuilder = false
    ): QueryBuilder|<?= $relationClassData['className'] ?>
    {
        $qb = $this
            ->getPublishedQuery()
            ->leftJoin(self::ALIAS.'.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug);
        if (null !== $<?= $relationClassData['lowerNames']['singular'] ?>) {
            $qb->andWhere(':<?= $relationClassData['lowerNames']['singular'] ?> IN ('.self::ALIAS.'.<?= $relationClassData['lowerNames']['plural'] ?>)')
                ->setParameter('<?= $relationClassData['lowerNames']['singular'] ?>', $<?= $relationClassData['lowerNames']['singular'] ?>);
        }

        $qb->setMaxResults(1);
        if ($returnQueryBuilder) {
            return $qb;
        }

        return $qb->getQuery()
            ->enableResultCache($this->cacheTtl)
            ->getOneOrNullResult();
    }
}

