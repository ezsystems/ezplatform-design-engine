<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;

class PHPStormPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->getParameter('ezdesign.phpstorm.enabled')) {
            return;
        }

        if (!$container->hasParameter('ezdesign.templates_path_map')) {
            return;
        }

        $pathConfig = [];
        $twigConfigPath = realpath($container->getParameter('ezdesign.phpstorm.twig_config_path'));
        foreach ($container->getParameter('ezdesign.templates_path_map') as $theme => $paths) {
            foreach ($paths as $path) {
                if ($theme !== '_override') {
                    $pathConfig[] = [
                        'namespace' => $theme,
                        'path' => $this->makeTwigPathRelative($path, $twigConfigPath),
                    ];
                }

                $pathConfig[] = [
                    'namespace' => 'ezdesign',
                    'path' => $this->makeTwigPathRelative($path, $twigConfigPath),
                ];
            }
        }

        (new Filesystem())->dumpFile(
            $twigConfigPath . '/ide-twig.json',
            json_encode(['namespaces' => $pathConfig], JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Converts absolute $path to a path relative to ide-twig.json config file.
     *
     * @param string $path       Absolute path
     * @param string $configPath Absolute path where ide-twig.json is stored
     *
     * @return string
     */
    private function makeTwigPathRelative($path, $configPath)
    {
        return trim(str_replace($configPath, '', $path), '/');
    }
}
