<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Media;

use Sylius\Component\Resource\Model\ResourceInterface;

interface FolderInterface extends ResourceInterface
{
    public function setParent(self|bool|Folder|null $param): void;

    public function getPath(): string;

    public function setName(string $name): void;
}
