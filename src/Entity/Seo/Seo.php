<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Seo;

use Adeliom\SyliusHappyCMSPlugin\Entity\Media\Media;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Seo implements \Stringable
{
    #[ORM\Column]
    public string $title;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(nullable: true)]
    public ?string $keywords;

    #[ORM\Column(nullable: true)]
    public ?string $canonical;

    #[ORM\Column(type: 'happy_cms_media_type', nullable: true)]
    public Media|string|null $cover;

    #[ORM\Column(nullable: true)]
    public ?string $key;

    /** @var bool */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN)]
    public ?bool $sitemap = true;

    /** @var array<int, string> */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::JSON)]
    public array $robots = [];

    public function __toString(): string
    {
        return $this->title;
    }
}
