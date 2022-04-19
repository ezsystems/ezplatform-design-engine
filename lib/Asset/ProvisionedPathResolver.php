<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Asset;

use Symfony\Component\Finder\Finder;

class ProvisionedPathResolver implements AssetPathResolverInterface, AssetPathProvisionerInterface
{
    /**
     * @var array
     */
    private $resolvedPaths;

    /**
     * @var AssetPathResolverInterface
     */
    private $innerResolver;

    /**
     * @var string
     */
    private $webRootDir;

    public function __construct(array $resolvedPaths, AssetPathResolverInterface $innerResolver, $webRootDir)
    {
        $this->resolvedPaths = $resolvedPaths;
        $this->innerResolver = $innerResolver;
        $this->webRootDir = $webRootDir;
    }

    /**
     * Looks for $path within pre-resolved paths for provided design.
     * If it cannot be found, fallbacks to original resolver.
     *
     * {@inheritdoc}
     */
    public function resolveAssetPath($path, $design)
    {
        if (!isset($this->resolvedPaths[$design][$path])) {
            return $this->innerResolver->resolveAssetPath($path, $design);
        }

        return $this->resolvedPaths[$design][$path];
    }

    public function provisionResolvedPaths(array $assetsPaths, $design)
    {
        $webrootDir = $this->webRootDir;
        $assetsLogicalPaths = [];
        foreach ($assetsPaths as $path) {
            $themePath = "$webrootDir/$path";
            $assetsLogicalPaths = array_merge($assetsLogicalPaths, $this->computeLogicalPathFromPhysicalAssets($themePath));
        }

        $resolvedPaths = [];
        foreach (array_unique($assetsLogicalPaths) as $logicalPath) {
            $resolvedPaths[$logicalPath] = $this->resolveAssetPath($logicalPath, $design);
        }

        return $resolvedPaths;
    }

    /**
     * Looks for physical assets within $themePath and computes their logical path (i.e. without full path to theme dir).
     *
     * Excludes "themes/" directory under a theme one, in order to avoid recursion.
     * This exclusion mainly applies to override directories,
     * e.g. "assets/", which is both an override dir and where app level themes can be defined.
     *
     * @param string $themePath
     *
     * @return array
     */
    private function computeLogicalPathFromPhysicalAssets($themePath)
    {
        if (!is_dir($themePath)) {
            return [];
        }

        $logicalPaths = [];
        /** @var \SplFileInfo $fileInfo */
        foreach ((new Finder())->files()->in($themePath)->exclude('themes')->followLinks()->ignoreUnreadableDirs() as $fileInfo) {
            $logicalPaths[] = trim(substr($fileInfo->getPathname(), strlen($themePath)), '/');
        }

        return $logicalPaths;
    }
}
