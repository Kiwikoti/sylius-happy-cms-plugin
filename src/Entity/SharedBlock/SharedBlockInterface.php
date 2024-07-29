<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\SharedBlock;

interface SharedBlockInterface
{
    public function getType(): ?string;

    public function getStatus(): bool;

    public function getKey(): ?string;

    public function getName(): ?string;

    /**
     * @return array<string, mixed>|null
     */
    public function getSettings(): ?array;
}
