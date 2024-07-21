<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Media;

interface FolderInterface
{
    public function setParent(self|bool|Folder|null $param): void;

    public function getPath(): string;

    public function setName(string $name): void;
}
