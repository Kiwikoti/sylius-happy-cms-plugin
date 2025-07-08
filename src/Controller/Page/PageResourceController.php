<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Controller\Page;

use Adeliom\SyliusEasyCrudPlugin\Controller\SyliusCrudResourceController;
use Adeliom\SyliusHappyCMSPlugin\Entity\Page\PageInterface;
use Adeliom\SyliusHappyCMSPlugin\Services\Cmf\RouteRenderService;
use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

class PageResourceController extends SyliusCrudResourceController
{
    public static function getSubscribedServices(): array
    {
        return [
            RouteRenderService::class => RouteRenderService::class,
            FlashBagInterface::class => "session.flash_bag",
        ];
    }

    public function clearCacheAction(Request $request): Response
    {
        $flashBag = $request->getSession()->getBag('flashes');
        try {
            /** @var RouteRenderService $service */
            $service = $this->container->get(RouteRenderService::class);
            $service->invalidCache();

            $flashBag->add('success', 'sylius_happy_cms.cache.successfully_cleared');
        } catch (\RuntimeException $exception) {
            try {
                $flashBag->add('error', 'sylius_happy_cms.cache.something_went_wrong');
            }
            catch (\RuntimeException $exception) {
                // DO nothing, flash service not available
            }
        }
        return $this->redirectToRoute('sylius_happy_cms_admin_page_index');
    }

    public function blockPreviewAction(Request $request): Response
    {
        $data = [];
        $blocks = [
            'block-demo-1' => array_merge([
                  'position' => '1',
                  'block_type' => 'Adeliom\\SyliusHappyCMSPlugin\\Block\\AccordionBlockType',
                  'block_published' => '1',
            ], $data),
        ];

        return $this->render('@SyliusHappyCMSPlugin/front/blocks/preview.html.twig', [
            'blocks' => $blocks,
            'preview' => true,
            'data' => $request->get('data'),
        ]);
    }
}
