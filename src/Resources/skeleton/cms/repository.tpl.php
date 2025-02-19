<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

if (
    isset($classNameDetail) && $classNameDetail instanceof ClassNameDetails &&
    isset($relationClassNameDetail, $scope)
) {
    $mainClassData = [
        'singular' => mb_strtolower(Str::asSnakeCase($classNameDetail->getShortName())),
        'plural' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($classNameDetail->getShortName()))),
    ];
    if ($relationClassNameDetail instanceof ClassNameDetails) {
        $relationClassData = [
            'singular' => mb_strtolower(Str::asSnakeCase($relationClassNameDetail->getShortName())),
            'plural' => mb_strtolower(Str::asSnakeCase(Str::singularCamelCaseToPluralCamelCase($relationClassNameDetail->getShortName()))),
        ];
    }
    ?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= str_replace('Entity', 'Repository', Str::getNamespace($classNameDetail->getFullName())) ?>;

use Adeliom\SyliusEasyCrudPlugin\Enum\ThreeStateStatusEnum;
use Adeliom\SyliusEasyCrudPlugin\Repository\TranslationRepositoryInterface;
use Adeliom\SyliusEasyCrudPlugin\Traits\TranslationRepositoryTrait;
<?php if ($relationClassNameDetail instanceof ClassNameDetails) { ?>
use App\Entity\HappyCMS\<?= ucfirst($scope) ?>\<?= $relationClassNameDetail->getShortName() ?>;
<?php } ?>
use App\Entity\HappyCMS\<?= ucfirst($scope) ?>\<?= $classNameDetail->getShortName() ?>;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<<?= $classNameDetail->getShortName() ?>>
 *
 * @method <?= $classNameDetail->getShortName() ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $classNameDetail->getShortName() ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $classNameDetail->getShortName() ?>[]    findAll()
 * @method <?= $classNameDetail->getShortName() ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $classNameDetail->getShortName() ?>Repository extends EntityRepository implements TranslationRepositoryInterface
{
    use TranslationRepositoryTrait;

    private const ALIAS = '<?= mb_strtolower($scope) ?>__<?= $mainClassData['singular'] ?>';

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
        string $locale,
        bool $returnQueryBuilder = false
    ): QueryBuilder|<?= $classNameDetail->getShortName() ?>|null
    {
        $qb = $this
            ->getPublishedQuery()
            ->leftJoin(self::ALIAS.'.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale);

        $qb->setMaxResults(1);
        if ($returnQueryBuilder) {
            return $qb;
        }

        return $qb->getQuery()
            ->enableResultCache($this->cacheTtl)
            ->getOneOrNullResult();
    }
}

<?php } ?>
