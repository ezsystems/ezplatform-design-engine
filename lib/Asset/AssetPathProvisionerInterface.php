<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Asset;

interface AssetPathProvisionerInterface
{
    /**
     * Pre-resolves assets paths for a given design from themes paths, where are stored physical assets.
     * Returns an map with asset logical path as key and its resolved path (relative to webroot dir) as value.
     * Example => ['images/foo.png' => 'asset/themes/some_theme/images/foo.png'].
     *
     * @param array  $assetsPaths
     * @param string $design
     *
     * @return array
     */
    public function provisionResolvedPaths(array $assetsPaths, $design);
}
