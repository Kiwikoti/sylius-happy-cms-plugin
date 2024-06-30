<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Controller\Routing;

use Adeliom\SyliusHappyCMSPlugin\EventListener\EntityRouteIndexer;
use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;
use Adeliom\SyliusHappyCMSPlugin\Security\ContentDocumentVoter;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\Metadata;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class RenderController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected RequestConfigurationFactory $requestConfigurationFactory,
        protected Environment $twig,
    ) {
    }

    public function renderAction(
        CmsRoutableInterface $contentDocument,
        Request $request,
    ): Response {
        /**
         * @var Route $route
         */
        $route = $request->attributes->get('routeDocument');

        [$metadata, $configuration] = $this->contentDocumentAsResource(
            get_class($contentDocument),
            $request,
        );

        $template = $contentDocument->getRouteTemplate();
        if (null === $template) {
            $template = '@SyliusHappyCMSPlugin/front/document/default.html.twig';
        }

        $controller = $contentDocument->getRouteController();
        if (null !== $controller) {
            try {
                $this->forward($controller, [
                    $contentDocument,
                    $request,
                ]);
            } catch (\RuntimeException $runtimeException) {
                throw $this->createAccessDeniedException($controller . ' not exists');
            }
        }

        if (true === $route->getOption(EntityRouteIndexer::OPTION_PREVIEW)) {
            $this->denyAccessUnlessGranted(ContentDocumentVoter::PREVIEW, $contentDocument);
        }

        if (!$contentDocument->isOnline()) {
            throw $this->createNotFoundException('Document is not published');
        }

        $this->twig->addGlobal('resource', $contentDocument);

        return $this->render($template, [
            'metadata' => $metadata,
            'configuration' => $configuration,
            'resource' => $contentDocument,
            'route' => $route,
            'preview' => $route->getOption(EntityRouteIndexer::OPTION_PREVIEW),
        ]);
    }

    private function contentDocumentAsResource(
        string $model,
        Request $request,
    ): array {
        try {
            /** @var array $resources */
            $resources = $this->container->get('parameter_bag')->get('sylius.resources');
        } catch (InvalidArgumentException $exception) {
            return [];
        }
        $metadata = $configuration = null;
        foreach ($resources as $alias => $parameters) {
            if ($parameters['classes']['model'] === $model) {
                $metadata = Metadata::fromAliasAndConfiguration($alias, $parameters);
                $configuration = $this->requestConfigurationFactory
                    ->create(
                        $metadata,
                        $request,
                    );
            }
        }

        return [$metadata, $configuration];
    }
}
