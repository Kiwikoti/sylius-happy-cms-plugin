<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Twig\Cmf;

use Adeliom\SyliusHappyCMSPlugin\Factory\CMS\CmsRoutableInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class
RouteExtension extends AbstractExtension
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly RouterInterface $router,
        private readonly ParameterBag $parameterBag,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('happy_cms_path', $this->getPath(...)),
            new TwigFunction('happy_cms_path_by_seo_key', $this->getPathBySeoKey(...)),
            new TwigFunction('happy_cms_path_by_key', $this->getPathByKey(...)),
            new TwigFunction('happy_cms_path_by_id', $this->getPathById(...)),
        ];
    }

    public function getPath(CmsRoutableInterface $object): ?string
    {
        try {
            return $this->router->generate(RouteObjectInterface::OBJECT_BASED_ROUTE_NAME, [
                RouteObjectInterface::ROUTE_OBJECT => $object->getOnlineRoute(),
            ]);
        } catch (NoResultException | NonUniqueResultException $e) {
            return '';
        }
    }

    public function getPathBySeoKey(string $key, string $resourceName = 'sylius_happy_cms.page', ?string $locale = null): ?string
    {
        try {
            $resources = $this->parameterBag->get('sylius.resources');
            $modelClass = $resources[$resourceName]['classes']['model'] ?? null;

            if (is_null($modelClass) || !is_a($modelClass, CmsRoutableInterface::class, true)) {
                throw new \InvalidArgumentException(sprintf('The resource "%s" must implement "%s".', $resourceName, CmsRoutableInterface::class));
            }

            $repository = $this->manager->getRepository($modelClass);

            if (is_null($repository) || !method_exists($repository, 'getBySeoKey')) {
                throw new \InvalidArgumentException(sprintf('The resource "%s" repository must have a method "%s".', $resourceName, 'getBySeoKey'));
            }

            if (is_null($locale)) {
                $locale = $this->requestStack->getCurrentRequest()?->getLocale() ?? 'en_US';
            }

            /** @var CmsRoutableInterface|null $object */
            $object = $repository->getBySeoKey($key, $locale);
            if (!is_null($object)) {
                return $this->router->generate(RouteObjectInterface::OBJECT_BASED_ROUTE_NAME, [
                    RouteObjectInterface::ROUTE_OBJECT => $object->getOnlineRoute(),
                ]);
            }
            return '';
        } catch (NoResultException | NonUniqueResultException $e) {
            return '';
        }
    }

    public function getPathByKey(string $key, string $resourceName = 'sylius_happy_cms.page'): ?string
    {
        try {
            $resources = $this->parameterBag->get('sylius.resources');
            $modelClass = $resources[$resourceName]['classes']['model'] ?? null;

            if (is_null($modelClass) || !is_a($modelClass, CmsRoutableInterface::class, true)) {
                throw new \InvalidArgumentException(sprintf('The resource "%s" must implement "%s".', $resourceName, CmsRoutableInterface::class));
            }

            $repository = $this->manager->getRepository($modelClass);

            if (is_null($repository) || !method_exists($repository, 'getByKey')) {
                throw new \InvalidArgumentException(sprintf('The resource "%s" repository must have a method "%s".', $resourceName, 'getByKey'));
            }

            /** @var CmsRoutableInterface|null $object */
            $object = $repository->getByKey($key);
            if (!is_null($object)) {
                return $this->router->generate(RouteObjectInterface::OBJECT_BASED_ROUTE_NAME, [
                    RouteObjectInterface::ROUTE_OBJECT => $object->getOnlineRoute(),
                ]);
            }
            return '';
        } catch (NoResultException | NonUniqueResultException $e) {
            return '';
        }
    }

    public function getPathById(int $id, string $resourceName = 'sylius_happy_cms.page'): ?string
    {
        try {
            $resources = $this->parameterBag->get('sylius.resources');
            $modelClass = $resources[$resourceName]['classes']['model'] ?? null;

            if (is_null($modelClass) || !is_a($modelClass, CmsRoutableInterface::class, true)) {
                throw new \InvalidArgumentException(sprintf('The resource "%s" must implement "%s".', $resourceName, CmsRoutableInterface::class));
            }

            $repository = $this->manager->getRepository($modelClass);

            if (is_null($repository)) {
                throw new \InvalidArgumentException(sprintf('The resource "%s" repository is not found.', $resourceName));
            }

            /** @var CmsRoutableInterface|null $object */
            $object = $repository->find($id);
            if (!is_null($object)) {
                return $this->router->generate(RouteObjectInterface::OBJECT_BASED_ROUTE_NAME, [
                    RouteObjectInterface::ROUTE_OBJECT => $object->getOnlineRoute(),
                ]);
            }
            return '';
        } catch (NoResultException | NonUniqueResultException $e) {
            return '';
        }
    }
}
