<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\Compiler;

use EzSystems\EzPlatformDesignEngineBundle\DataCollector\TwigDataCollector;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Registers defined designs as valid Twig namespaces.
 * A design is a collection of ordered themes (in fallback order).
 * A theme is a collection of one or several template paths.
 */
class TwigThemePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!($container->hasParameter('kernel.bundles') && $container->hasDefinition('ezdesign.twig_theme_loader'))) {
            return;
        }

        $globalViewsDir = $container->getParameter('kernel.root_dir') . '/Resources/views';
        if (!is_dir($globalViewsDir)) {
            (new Filesystem())->mkdir($globalViewsDir);
        }
        $themesPathMap = [
            '_override' => $container->getParameter('ezdesign.templates_override_paths'),
        ];
        $finder = new Finder();
        // Look for themes in bundles.
        foreach ($container->getParameter('kernel.bundles') as $bundleName => $bundleClass) {
            $bundleReflection = new ReflectionClass($bundleClass);
            $bundleViewsDir = dirname($bundleReflection->getFileName()) . '/Resources/views';
            $themeDir = $bundleViewsDir . '/themes';
            if (!is_dir($themeDir)) {
                continue;
            }

            /** @var \Symfony\Component\Finder\SplFileInfo $directoryInfo */
            foreach ($finder->directories()->in($themeDir)->depth('== 0') as $directoryInfo) {
                $themesPathMap[$directoryInfo->getBasename()][] = $directoryInfo->getRealPath();
            }
        }

        $twigLoaderDef = $container->findDefinition('ezdesign.twig_theme_loader');
        // Now look for themes at application level (app/Resources/views/themes)
        $appLevelThemesDir = $globalViewsDir . '/themes';
        if (is_dir($appLevelThemesDir)) {
            foreach ((new Finder())->directories()->in($appLevelThemesDir)->depth('== 0') as $directoryInfo) {
                $theme = $directoryInfo->getBasename();
                $themePaths = isset($themesPathMap[$theme]) ? $themesPathMap[$theme] : [];
                // Application level paths are always top priority.
                array_unshift($themePaths, $directoryInfo->getRealPath());
                $themesPathMap[$theme] = $themePaths;
            }
        }

        // Now merge with already configured template theme paths
        // Template theme paths defined via config will always have less priority than convention based paths
        $themesPathMap = array_merge_recursive($themesPathMap, $container->getParameter('ezdesign.templates_path_map'));

        // De-duplicate the map
        foreach ($themesPathMap as $theme => &$paths) {
            $paths = array_unique($paths);
        }

        foreach ($container->getParameter('ezdesign.design_list') as $designName => $themeFallback) {
            // Always add _override theme first.
            array_unshift($themeFallback, '_override');
            foreach ($themeFallback as $theme) {
                // Theme could not be found in expected directories, just ignore.
                if (!isset($themesPathMap[$theme])) {
                    continue;
                }

                foreach ($themesPathMap[$theme] as $path) {
                    $twigLoaderDef->addMethodCall('addPath', [$path, $designName]);
                }
            }
        }

        $themesList = $container->getParameter('ezdesign.themes_list');
        $container->setParameter('ezdesign.themes_list', array_unique(
            array_merge($themesList, array_keys($themesPathMap)))
        );
        $container->setParameter('ezdesign.templates_path_map', $themesPathMap);

        $twigDataCollector = $container->findDefinition('data_collector.twig');
        $twigDataCollector->setClass(TwigDataCollector::class);

        if (count($twigDataCollector->getArguments()) === 1) {
            // In versions of Symfony prior to 3.4, "data_collector.twig" had only one
            // argument, we're adding "twig" service to satisfy constructor overriden
            // in EzSystems\EzPlatformDesignEngineBundle\DataCollector\TwigDataCollector
            // which is based on Symfony 3.4 version of base TwigDataCollector
            $twigDataCollector->addArgument(new Reference('twig'));
        }

        $twigDataCollector->addArgument(new Reference('ezdesign.template_path_registry'));
    }
}
