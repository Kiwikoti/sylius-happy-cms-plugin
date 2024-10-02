<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Config;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface ConfigInterface extends ResourceInterface, TranslatableInterface
{
    public function getType(): ?string;

    public function getKey(): ?string;

    public function setKey(?string $key): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function setType(mixed $type): void;

    /**
     * @return Collection<int, ConfigTranslationInterface>
     */
    public function getTranslations(): Collection;
}
