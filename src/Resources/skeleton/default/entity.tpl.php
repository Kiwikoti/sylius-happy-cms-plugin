<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

if (
    isset($classNameDetail) && $classNameDetail instanceof ClassNameDetails
) {
    ?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= Str::getNamespace($classNameDetail->getFullName()) ?>;

use Adeliom\SyliusHappyCMSPlugin\Entity\<?= $classNameDetail->getRelativeNameWithoutSuffix() ?>\<?= $classNameDetail->getShortName() ?> as
    Base<?= $classNameDetail->getShortName() ?>;<?php if ($classNameDetail->getRelativeNameWithoutSuffix() ===
            $classNameDetail->getRelativeName()) { ?>
use <?= str_replace('Entity', 'Repository', Str::getNamespace($classNameDetail->getFullName())) ?>\<?= $classNameDetail->getShortName() ?>Repository;
<?php } ?>
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[Serializer\ExclusionPolicy('ALL')]
<?php if ($classNameDetail->getRelativeNameWithoutSuffix() ===
        $classNameDetail->getRelativeName()) { ?>
#[ORM\Entity(repositoryClass: <?= $classNameDetail->getShortName() ?>Repository::class)]
<?php } else { ?>
#[ORM\Entity]
<?php } ?>
#[ORM\Table(name: 'sylius_happy_cms__<?= Str::asSnakeCase($classNameDetail->getShortName()) ?>')]
class <?= $classNameDetail->getShortName() ?> extends Base<?= $classNameDetail->getShortName() ?> {
}
<?php } ?>
