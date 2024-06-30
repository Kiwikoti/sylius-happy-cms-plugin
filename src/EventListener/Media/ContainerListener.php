<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\EventListener\Media;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerListener
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
