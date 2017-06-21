<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngine\Tests\Asset;

use EzSystems\EzPlatformDesignEngine\Asset\AssetPathResolverInterface;
use EzSystems\EzPlatformDesignEngine\Asset\ThemePackage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Asset\PackageInterface;

class ThemePackageTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\EzSystems\EzPlatformDesignEngine\Asset\AssetPathResolverInterface
     */
    private $assetPathResolver;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Asset\PackageInterface
     */
    private $innerPackage;

    protected function setUp()
    {
        parent::setUp();

        $this->assetPathResolver = $this->createMock(AssetPathResolverInterface::class);
        $this->innerPackage = $this->createMock(PackageInterface::class);
    }

    public function testGetUrl()
    {
        $assetPath = 'images/foo.png';
        $fullAssetPath = 'assets/' . $assetPath;
        $currentDesign = 'foo';

        $this->assetPathResolver
            ->expects($this->once())
            ->method('resolveAssetPath')
            ->with($assetPath, $currentDesign)
            ->willReturn($fullAssetPath);
        $this->innerPackage
            ->expects($this->once())
            ->method('getUrl')
            ->with($fullAssetPath)
            ->willReturn("/$fullAssetPath");

        $package = new ThemePackage($this->assetPathResolver, $this->innerPackage);
        $package->setCurrentDesign($currentDesign);
        self::assertSame("/$fullAssetPath", $package->getUrl($assetPath));
    }

    public function testGetVersion()
    {
        $assetPath = 'images/foo.png';
        $fullAssetPath = 'assets/' . $assetPath;
        $currentDesign = 'foo';

        $this->assetPathResolver
            ->expects($this->once())
            ->method('resolveAssetPath')
            ->with($assetPath, $currentDesign)
            ->willReturn($fullAssetPath);
        $version = 'v1';
        $this->innerPackage
            ->expects($this->once())
            ->method('getVersion')
            ->with($fullAssetPath)
            ->willReturn($version);

        $package = new ThemePackage($this->assetPathResolver, $this->innerPackage);
        $package->setCurrentDesign($currentDesign);
        self::assertSame($version, $package->getVersion($assetPath));
    }
}
