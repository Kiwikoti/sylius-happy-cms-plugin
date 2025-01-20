<?php declare(strict_types=1);

use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

if (
    isset($classNameDetail) && $classNameDetail instanceof ClassNameDetails &&
    isset($scope)
) {
    ?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= str_replace('Entity', 'Controller', Str::getNamespace($classNameDetail->getFullName())) ?>;

use Adeliom\SyliusEasyCrudPlugin\Controller\SyliusCrudResourceController;

class <?= $classNameDetail->getShortName() ?>Controller extends SyliusCrudResourceController {
}
<?php } ?>
