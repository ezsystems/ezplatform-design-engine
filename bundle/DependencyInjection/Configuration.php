<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngineBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessConfiguration
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ezdesign');

        $rootNode
            ->children()
                ->arrayNode('design_list')
                    ->useAttributeAsKey('design_name')
                    ->example(['my_design' => ['theme1', 'theme2']])
                    ->prototype('array')
                        ->info('A design is a labeled collection of themes. Theme order defines the fallback order.')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
                ->arrayNode('templates_theme_paths')
                    ->useAttributeAsKey('theme')
                    ->example(['some_theme' => ['/var/foo/some_theme_dir', '/another/theme/dir']])
                    ->info('Collection of template paths by theme.')
                    ->prototype('array')
                        ->info('Each path MUST exist')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
                ->arrayNode('templates_override_paths')
                    ->info('Directories to add to the override list for templates. Those directories will be checked before theme directories.')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('disable_assets_pre_resolution')
                    ->info('If set to true, assets path won\'t be pre-resolved at compile time.')
                    ->defaultValue('%kernel.debug%')
                ->end()
                ->arrayNode('phpstorm')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultValue('%kernel.debug%')->info('Activates PHPStorm support')->end()
                        ->scalarNode('twig_config_path')
                            ->info('Path where to store PHPStorm configuration file for additional Twig namespaces (ide-twig.json).')
                            ->defaultValue('%kernel.root_dir%/..')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
