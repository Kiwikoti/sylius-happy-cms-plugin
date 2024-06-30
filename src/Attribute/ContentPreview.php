<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ContentPreview
{
    public function __construct(
        private array $roles,
    ) {
    }

    /** @return array<string> */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
