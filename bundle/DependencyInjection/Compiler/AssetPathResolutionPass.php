<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\Compiler;

use EzSystems\EzPlatformDesignEngine\Asset\AssetPathProvisionerInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Resolves assets theme paths.
 * Avoids multiple I/O calls at runtime when looking for the right asset path.
 *
 * Will loop over registered theme paths for each design.
 * Within each theme path, will look for any files in order to make a list of all available assets.
 * Each asset is then regularly processed through the AssetPathResolver, like if it were called by asset() Twig function.
 */
class AssetPathResolutionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('ezdesign.asset_resolution.disabled')) {
            return;
        }

        $resolvedPathsByDesign = $this->preResolveAssetsPaths(
            $container->get('ezdesign.asset_path_resolver.provisioned'),
            $container->getParameter('ezdesign.assets_path_map')
        );

        $container->setParameter('ezdesign.asset_resolved_paths', $resolvedPathsByDesign);
        $container->findDefinition('ezdesign.asset_path_resolver.provisioned')->replaceArgument(0, $resolvedPathsByDesign);
        $container->setAlias('ezdesign.asset_path_resolver', new Alias('ezdesign.asset_path_resolver.provisioned'));
    }

    private function preResolveAssetsPaths(AssetPathProvisionerInterface $provisioner, array $designPathMap)
    {
        $resolvedPathsByDesign = [];
        foreach ($designPathMap as $design => $paths) {
            $resolvedPathsByDesign[$design] = $provisioner->provisionResolvedPaths($paths, $design);
        }

        return $resolvedPathsByDesign;
    }
}
