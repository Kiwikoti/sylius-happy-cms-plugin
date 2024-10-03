<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

if (
    isset($classNameDetail) && $classNameDetail instanceof ClassNameDetails &&
    isset($scope, $addRepo)
) {
    ?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= Str::getNamespace($classNameDetail->getFullName()) ?>;

use <?= str_replace('Admin', 'Entity', Str::getNamespace($classNameDetail->getFullName())) ?>\<?= str_replace('Admin', '', $classNameDetail->getShortName()) ?>;
use Adeliom\SyliusHappyCMSPlugin\Admin\<?= $scope ?>\<?= $classNameDetail->getShortName() ?> as Base<?=
    $classNameDetail->getShortName() ?>;

class <?= $classNameDetail->getShortName() ?> extends Base<?= $classNameDetail->getShortName() ?><?php echo "\n"; ?>{
    public static function getEntityFqcn(): string
    {
        return <?= str_replace('Admin', '', $classNameDetail->getShortName()) ?>::class;
    }
}
<?php } ?>
