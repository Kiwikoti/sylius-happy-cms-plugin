<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Doctrine\Query\Menu;

interface AllMenuItemsInterface
{
    /** @return array<array-key, mixed> */
    public function getArrayResult(int $menuId): array;
}
