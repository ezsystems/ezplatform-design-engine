<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngineBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('default_settings.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $processor = new ConfigurationProcessor($container, 'ezdesign');

        $this->configureDesigns($config, $processor, $container);
    }

    private function configureDesigns(array $config, ConfigurationProcessor $processor, ContainerBuilder $container)
    {
        // Always add "standard" design to the list (defaults to application level & override paths only)
        $config['design_list'] += ['standard' => []];
        $container->setParameter('ezdesign.design_list', $config['design_list']);
        $container->setParameter('ezdesign.templates_override_paths', $config['templates_override_paths']);
        $container->setParameter('ezdesign.templates_path_map', $config['templates_theme_paths']);
        $container->setParameter('ezdesign.asset_resolution.disabled', $config['disable_assets_pre_resolution']);

        // PHPStorm settings
        $container->setParameter('ezdesign.phpstorm.enabled', $config['phpstorm']['enabled']);
        $container->setParameter('ezdesign.phpstorm.twig_config_path', $config['phpstorm']['twig_config_path']);
    }
}
