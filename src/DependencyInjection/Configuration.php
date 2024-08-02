<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\DependencyInjection;

use Adeliom\SyliusHappyCMSPlugin\Admin\Page\PageAdmin;
use Adeliom\SyliusHappyCMSPlugin\Admin\Page\PageAdminInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\Config\Config;
use Adeliom\SyliusHappyCMSPlugin\Entity\Media\Folder;
use Adeliom\SyliusHappyCMSPlugin\Entity\Media\Media;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\Menu;
use Adeliom\SyliusHappyCMSPlugin\Entity\Menu\MenuItem;
use Adeliom\SyliusHappyCMSPlugin\Entity\Page\Page;
use Adeliom\SyliusHappyCMSPlugin\Entity\Page\PageInterface;
use Adeliom\SyliusHappyCMSPlugin\Entity\SharedBlock\SharedBlock;
use Adeliom\SyliusHappyCMSPlugin\Repository\Config\ConfigRepository;
use Adeliom\SyliusHappyCMSPlugin\Repository\Menu\MenuItemRepository;
use Adeliom\SyliusHappyCMSPlugin\Repository\Menu\MenuRepository;
use Adeliom\SyliusHappyCMSPlugin\Repository\Page\PageRepository;
use Adeliom\SyliusHappyCMSPlugin\Repository\Page\PageRepositoryInterface;
use Adeliom\SyliusHappyCMSPlugin\Repository\SharedBlock\SharedBlockRepository;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_happy_cms');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('page')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('page_class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(function ($value) {
                                    if (!class_exists($value) || !is_a($value, PageInterface::class, true)) {
                                        throw new InvalidConfigurationException(sprintf('Page class must be a valid class extending %s. "%s" given.', Page::class, $value));
                                    }

                                    return $value;
                                })
                            ->end()
                        ->end()

                        ->scalarNode('page_repository')
                            ->defaultValue(PageRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(function ($value) {
                                    if (!class_exists($value) || !is_a($value, PageRepositoryInterface::class, true)) {
                                        throw new InvalidConfigurationException(sprintf('Page repository must be a valid class extending %s. "%s" given.', PageRepository::class, $value));
                                    }

                                    return $value;
                                })
                            ->end()
                        ->end()

                        ->scalarNode('page_admin')
                            ->defaultValue(PageAdmin::class)
                            ->validate()
                                ->ifString()
                                ->then(function ($value) {
                                    if (!class_exists($value) || !is_a($value, PageAdminInterface::class, true)) {
                                        throw new InvalidConfigurationException(sprintf('Page repository must be a valid class extending %s. "%s" given.', PageRepository::class, $value));
                                    }

                                    return $value;
                                })
                            ->end()
                        ->end()

                        ->booleanNode('sitemap')
                            ->defaultValue(true)
                        ->end()

                    ->end()
                ->end()

                ->arrayNode('seo')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable_profiler')->defaultValue('%kernel.debug%')->end()
                        ->arrayNode('ignore_profiler')
                            ->defaultValue([
                                   '^/admin*',
                               ])->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('title')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('separator')->defaultValue('|')->end()
                                ->scalarNode('suffix')->defaultValue('')->end()
                            ->end()
                        ->end()
                        ->arrayNode('breadcrumbs')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue('breadcrumb')->end()
                                ->scalarNode('item_class')->defaultValue('breadcrumb-item')->end()
                                ->scalarNode('link_class')->defaultValue('')->end()
                                ->scalarNode('current_class')->defaultValue('active')->end()
                                ->scalarNode('separator')->defaultValue('>')->end()
                                ->scalarNode('separator_class')->defaultValue('breadcrumb-separator')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('config')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('config_class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(static function ($value) {
                                    if (!class_exists($value) || !is_a($value, Config::class, true)) {
                                        throw new InvalidConfigurationException(sprintf('Config class must be a valid class extending %s. "%s" given.', Config::class, $value));
                                    }

                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('config_repository')
                            ->defaultValue(ConfigRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(static function ($value) {
                                    if (!class_exists($value) || !is_a($value, ConfigRepository::class, true)) {
                                        throw new InvalidConfigurationException(sprintf('Config repository must be a valid class extending %s. "%s" given.', ConfigRepository::class, $value));
                                    }

                                    return $value;
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('menu')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('menu')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')
                                    ->isRequired()
                                    ->validate()
                                        ->ifString()
                                        ->then(function ($value) {
                                            if (!class_exists($value) || !is_a($value, Menu::class, true)) {
                                                throw new InvalidConfigurationException(sprintf('Entry class must be a valid class extending %s. "%s" given.', Menu::class, $value));
                                            }

                                            return $value;
                                        })
                                    ->end()
                                ->end()
                                ->scalarNode('repository')
                                    ->defaultValue(MenuRepository::class)
                                    ->validate()
                                        ->ifString()
                                        ->then(function ($value) {
                                            if (!class_exists($value) || !is_a($value, MenuRepository::class, true)) {
                                                throw new InvalidConfigurationException(sprintf('Entry repository must be a valid class extending %s. "%s" given.', MenuRepository::class, $value));
                                            }

                                            return $value;
                                        })
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('menu_item')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')
                                    ->isRequired()
                                    ->validate()
                                        ->ifString()
                                        ->then(function ($value) {
                                            if (!class_exists($value) || !is_a($value, MenuItem::class, true)) {
                                                throw new InvalidConfigurationException(sprintf('Category class must be a valid class extending %s. "%s" given.', MenuItem::class, $value));
                                            }

                                            return $value;
                                        })
                                    ->end()
                                ->end()
                                ->scalarNode('repository')
                                    ->defaultValue(MenuItemRepository::class)
                                    ->validate()
                                        ->ifString()
                                        ->then(function ($value) {
                                            if (!class_exists($value) || !is_a($value, MenuItemRepository::class, true)) {
                                                throw new InvalidConfigurationException(sprintf('Category repository must be a valid class extending %s. "%s" given.', MenuItemRepository::class, $value));
                                            }

                                            return $value;
                                        })
                                    ->end()
                                ->end()
                            ->end()
                    ->end()
                    ->arrayNode('cache')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('enabled')->defaultFalse()->end()
                            ->integerNode('ttl')->defaultValue(300)->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('media')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('storage_name')
                            ->defaultValue('default.storage')
                        ->end()
                        ->scalarNode('base_url')
                            ->defaultValue('/')
                        ->end()
                        ->scalarNode('media_entity')
                            ->isRequired()
                            ->validate()
                            ->ifString()
                            ->then(static function ($value) {
                                if (!class_exists($value) || !is_a($value, Media::class, true)) {
                                    throw new InvalidConfigurationException(sprintf('Media class must be a valid class extending %s. "%s" given.', Media::class, $value));
                                }

                                return $value;
                            })
                            ->end()
                        ->end()
                        ->scalarNode('folder_entity')
                            ->isRequired()
                            ->validate()
                            ->ifString()
                            ->then(static function ($value) {
                                if (!class_exists($value) || !is_a($value, Folder::class, true)) {
                                    throw new InvalidConfigurationException(sprintf('Media Folder class must be a valid class extending %s. "%s" given.', Folder::class, $value));
                                }

                                return $value;
                            })
                            ->end()
                        ->end()
                        ->scalarNode('ignore_files')
                            ->defaultValue('/^\..*/')
                        ->end()
                        ->scalarNode('allowed_fileNames_chars')
                            ->defaultValue("\._\-\'\s\(\),")
                        ->end()
                        ->scalarNode('allowed_folderNames_chars')
                            ->defaultValue("_\-\s")
                        ->end()
                        ->arrayNode('unallowed_mimes')
                            ->scalarPrototype()->end()
                            ->defaultValue([
                                'php',
                                'java',
                            ])
                        ->end()
                        ->arrayNode('locales')
                            ->scalarPrototype()->end()
                            ->defaultValue([
                                'en_US',
                                'de_DE',
                                'fr_FR',
                                'es_ES',
                                'es_MX',
                                'pl_PL',
                                'pt_PT',
                                'zh_CN',
                            ])
                        ->end()
                        ->arrayNode('unallowed_ext')
                            ->defaultValue([
                                'php',
                                'jav',
                                'py',
                            ])
                            ->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('extended_mimes')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('image')->scalarPrototype()->end()->isRequired()->defaultValue(['binary/octet-stream'])->end()
                                ->arrayNode('archive')->scalarPrototype()->end()->isRequired()->defaultValue(['application/x-tar', 'application/zip'])->end()
                            ->end()
                        ->end()
                        ->scalarNode('sanitized_text')
                            ->defaultValue('uniqid')
                        ->end()
                        ->scalarNode('last_modified_format')
                            ->defaultValue('Y-m-d')
                        ->end()
                        ->booleanNode('hide_files_ext')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('get_folder_info')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('enable_broadcasting')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('enable_generating_alts')
                            ->defaultFalse()
                        ->end()
                        ->integerNode('pagination_amount')
                            ->defaultValue(50)
                            ->min(4)
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('block')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('block_class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(static function ($value) {
                                    if (!class_exists($value) || !is_a($value, SharedBlock::class, true)) {
                                        throw new InvalidConfigurationException(sprintf('Block class must be a valid class extending %s. "%s" given.', SharedBlock::class, $value));
                                    }

                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('block_repository')
                            ->defaultValue(SharedBlockRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(static function ($value) {
                                    if (!class_exists($value) || !is_a($value, SharedBlockRepository::class, true)) {
                                        throw new InvalidConfigurationException(sprintf('Block repository must be a valid class extending %s. "%s" given.', SharedBlockRepository::class, $value));
                                    }

                                    return $value;
                                })
                            ->end()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
