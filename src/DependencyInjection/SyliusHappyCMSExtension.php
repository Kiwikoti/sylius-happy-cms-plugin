<?php

namespace Adeliom\SyliusHappyCMSPlugin\DependencyInjection;

use Adeliom\SyliusHappyCMSPlugin\Factory\Block\BlockInterface;
use Adeliom\SyliusHappyCMSPlugin\Factory\SharedBlock\SharedBlockInterface;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\FieldConfiguratorInterface;
use Adeliom\SyliusHappyCMSPlugin\Services\Seo\Sitemap\SitemapDumperInterface;
use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SyliusHappyCMSExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->processPageConfiguration($config['page'], $container);
        $this->processSeoConfiguration($config['seo'], $container);
        $this->processConfigConfiguration($config['config'], $container);
        $this->processMenuConfiguration($config['menu'], $container);
        $this->processBlockConfiguration($config['block'], $container);
        $this->processMediaConfiguration($config['media'], $container);

        $container->registerForAutoconfiguration(BlockInterface::class)
            ->addTag('adeliom.sylius.cms.block')
        ;

        $container->registerForAutoconfiguration(SharedBlockInterface::class)
            ->addTag('adeliom.sylius.cms.shared_block')
        ;

        $container->registerForAutoconfiguration(SitemapDumperInterface::class)
            ->addTag('happy_cms.seo.sitemap_dumpable')
        ;

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/media'));
        $loader->load('services.xml');
    }

    private function processPageConfiguration(array $config, ContainerBuilder $container)
    {
        foreach ($config as $key => $value) {
            $container->setParameter('happy_cms.page.'.$key, $value);
        }
    }

    private function processSeoConfiguration(array $config, ContainerBuilder $container)
    {
        foreach ($config as $key => $value) {
            $container->setParameter('happy_cms.seo.'.$key, $value);
        }
    }

    private function processConfigConfiguration(array $config, ContainerBuilder $container)
    {
        foreach ($config as $key => $value) {
            $container->setParameter('happy_cms.config.'.$key, $value);
        }
    }

    private function processMenuConfiguration(array $config, ContainerBuilder $container)
    {
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $type => $class) {
                    $container->setParameter(sprintf('happy_cms.menu.%s.%s', $key, $type), $class);
                }
            }
            $container->setParameter(sprintf('happy_cms.menu.%s', $key), $value);
        }
    }

    private function processBlockConfiguration(array $config, ContainerBuilder $container)
    {
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $type => $class) {
                    $container->setParameter(sprintf('happy_cms.block.%s.%s', $key, $type), $class);
                }
            }
            $container->setParameter(sprintf('happy_cms.block.%s', $key), $value);
        }
    }

    private function processMediaConfiguration(array $config, ContainerBuilder $container)
    {
        foreach ($config as $key => $value) {
            $container->setParameter('happy_cms.media.'.$key, $value);
        }

        $container->setAlias('happy.cms.media.storage', $container->getParameter('happy_cms.media.storage_name'));
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);

        $configs = $container->getExtensionConfig('media');
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $config = $config['media'];

        $container->prependExtensionConfig('media', $config);
        $twigConfig = [];
        $twigConfig['paths'][__DIR__.'/../Resources/views/media'] = 'media';
        $twigConfig['globals']['happy_cms'] = [];
        $twigConfig['globals']['happy_cms']['media'] = [];
        foreach ($config as $k => $v) {
            $twigConfig['globals']['happy_cms']['media'][$k] = $v;
        }

        $container->prependExtensionConfig('twig', $twigConfig);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'DoctrineMigrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@SyliusHappyCMSPlugin/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }

    public function getAlias(): string
    {
        return 'sylius_happy_cms';
    }
}
