<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngineBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class EzPlatformDesignEngineExtension extends Extension
{
    public function getAlias()
    {
        return 'ezdesign';
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration();
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('default_settings.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $processor = new ConfigurationProcessor($container, 'ezdesign');

        $this->configureDesigns($config, $processor, $container);
    }

    private function configureDesigns(array $config, ConfigurationProcessor $processor, ContainerBuilder $container)
    {
        // Always add _base theme to the list.
        foreach ($config['design']['list'] as $design => &$themes) {
            $themes[] = '_base';
        }
        $container->setParameter('ezdesign.design_list', $config['design']['list']);
        $container->setParameter('ezdesign.templates_override_paths', $config['design']['override_paths']);
        $container->setParameter('ezdesign.asset_resolution.disabled', $config['design']['disable_assets_pre_resolution']);

        // PHPStorm settings
        $container->setParameter('ezdesign.phpstorm.enabled', $config['phpstorm']['enabled']);
        $container->setParameter('ezdesign.phpstorm.twig_config_path', $config['phpstorm']['twig_config_path']);

        // SiteAccess aware settings
        $processor->mapConfig(
            $config,
            function ($scopeSettings, $currentScope, ContextualizerInterface $contextualizer) use ($config) {
                if (isset($scopeSettings['design'])) {
                    if (!isset($config['design']['list'][$scopeSettings['design']])) {
                        throw new InvalidArgumentException(
                            "Selected design for $currentScope '{$scopeSettings['design']}' is invalid. Did you forget to define it?"
                        );
                    }

                    $contextualizer->setContextualParameter('design', $currentScope, $scopeSettings['design']);
                }
            }
        );
    }
}
