<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Traits\Seo;

use Adeliom\SyliusHappyCMSPlugin\Entity\Seo\Seo;
use Doctrine\ORM\Mapping as ORM;

trait EntitySeoTrait
{
    #[ORM\Embedded(class: \Adeliom\SyliusHappyCMSPlugin\Entity\Seo\Seo::class)]
    protected Seo $seo;

    public function __construct()
    {
        $this->seo = new Seo();
    }

    public function setSeo(Seo $seo): void
    {
        $this->seo = $seo;
    }

    public function getSeo(): Seo
    {
        return $this->seo;
    }
}
