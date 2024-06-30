<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class MediaLoader extends Loader
{
    public function load($resource, ?string $type = null): RouteCollection
    {
        $routes = new RouteCollection();

        $resource = '@SyliusHappyCMSPlugin/config/media/routes.xml';
        $type = 'xml';

        $importedRoutes = $this->import($resource, $type);

        $routes->addCollection($importedRoutes);

        return $routes;
    }

    public function supports($resource, ?string $type = null): bool
    {
        return $type === 'attribute';
    }
}
