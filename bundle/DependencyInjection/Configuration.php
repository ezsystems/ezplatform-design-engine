<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
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
                ->arrayNode('design')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('list')
                            ->useAttributeAsKey('design_name')
                            ->example(['my_design' => ['theme1', 'theme2']])
                            ->prototype('array')
                                ->info('A design is a labeled collection of themes. Theme order defines the fallback order.')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                        ->arrayNode('override_paths')
                            ->info('Directories to add to the override list for templates. Those directories will be checked before theme directories.')
                            ->prototype('scalar')->end()
                        ->end()
                        ->booleanNode('disable_assets_pre_resolution')
                            ->info('If set to true, assets path won\'t be pre-resolved at compile time.')
                            ->defaultValue('%kernel.debug%')
                        ->end()
                    ->end()
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

        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->scalarNode('design')
                ->cannotBeEmpty()
                ->info('Name of the design to use. Must be one of the declared ones in the "design" key.')
            ->end()
            ->arrayNode('twig_globals')
                ->info('Variables available in all Twig templates for current SiteAccess.')
                ->normalizeKeys(false)
                ->useAttributeAsKey('variable_name')
                ->example(array('foo' => '"bar"', 'pi' => 3.14))
                ->prototype('variable')->end()
            ->end();

        return $treeBuilder;
    }
}
