<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Doctrine\Query\Page;

interface AllPagesInterface
{
    /** @return array<array-key, mixed> */
    public function getArrayResult(): array;
}
