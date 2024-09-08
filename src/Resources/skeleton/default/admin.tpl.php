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

use Adeliom\SyliusHappyCMSPlugin\Admin\<?= $classNameDetail->getRelativeNameWithoutSuffix() ?>\<?= $classNameDetail->getShortName() ?> as Base<?=
    $classNameDetail->getShortName() ?>;

class <?= $classNameDetail->getShortName() ?> extends Base<?= $classNameDetail->getShortName() ?>
{}
<?php } ?>
