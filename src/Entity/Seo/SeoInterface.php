<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Seo;

use Adeliom\SyliusHappyCMSPlugin\Entity\Media\MediaInterface;

interface SeoInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getKeywords(): ?string;

    public function setKeywords(?string $keywords): void;

    public function getCanonical(): ?string;

    public function setCanonical(?string $canonical): void;

    public function getCover(): MediaInterface|int|null;

    public function setCover(MediaInterface|int|null $cover): void;

    public function getKey(): ?string;

    public function setKey(?string $key): void;

    public function getSitemap(): ?bool;

    public function setSitemap(?bool $sitemap): void;

    /**
     * @return string[]
     */
    public function getRobots(): array;

    /**
     * @param string[] $robots
     */
    public function setRobots(array $robots): void;

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public static function normalizeFormData(array $data): array;
}
