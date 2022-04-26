<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Asset;

use EzSystems\EzPlatformDesignEngine\DesignAwareInterface;
use EzSystems\EzPlatformDesignEngine\DesignAwareTrait;
use Symfony\Component\Asset\PackageInterface;

class ThemePackage implements PackageInterface, DesignAwareInterface
{
    use DesignAwareTrait;

    /**
     * @var AssetPathResolverInterface
     */
    private $pathResolver;

    /**
     * @var \Symfony\Component\Asset\PackageInterface
     */
    private $innerPackage;

    public function __construct(AssetPathResolverInterface $pathResolver, PackageInterface $innerPackage)
    {
        $this->pathResolver = $pathResolver;
        $this->innerPackage = $innerPackage;
    }

    public function getUrl($path)
    {
        return $this->innerPackage->getUrl($this->pathResolver->resolveAssetPath($path, $this->getCurrentDesign()));
    }

    public function getVersion($path)
    {
        return $this->innerPackage->getVersion($this->pathResolver->resolveAssetPath($path, $this->getCurrentDesign()));
    }
}
