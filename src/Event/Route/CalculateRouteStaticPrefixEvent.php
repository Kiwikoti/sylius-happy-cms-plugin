<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Event\Route;

use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Resource\Model\TranslatableInterface;
use Symfony\Contracts\EventDispatcher\Event;

class CalculateRouteStaticPrefixEvent extends Event
{
    protected string $routeStaticPrefix = '';

    /** @param array{
     *     metadata: \Sylius\Resource\Metadata\Metadata,
     *     configuration: \Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration,
     *     resource: \Sylius\Component\Resource\Model\ResourceInterface,
     *     route: \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route,
     *     preview: bool
     * } $parameters
     */
    public function __construct(
        protected CmsRoutableInterface $entity,
        protected TranslationInterface $translation,
        protected ?bool $isPreview = false,
    ) {}

    public function getEntity(): CmsRoutableInterface
    {
        return $this->entity;
    }

    public function getTranslation(): TranslationInterface
    {
        return $this->translation;
    }

    public function isPreview(): bool
    {
        return $this->isPreview ?: false;
    }

    public function getRouteStaticPrefix(): string
    {
        return $this->routeStaticPrefix;
    }

    public function setRouteStaticPrefix(string $routeStaticPrefix): void
    {
        $this->routeStaticPrefix = $routeStaticPrefix;
    }

}
