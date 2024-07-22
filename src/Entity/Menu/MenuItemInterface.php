<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Menu;

interface MenuItemInterface
{
    public function getId(): ?int;

    public function getLft(): ?int;

    public function getRgt(): ?int;

    public function getRoot(): ?int;

    public function getLvl(): ?int;

    public function getPosition(): ?int;
}
