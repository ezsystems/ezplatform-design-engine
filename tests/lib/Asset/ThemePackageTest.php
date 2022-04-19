<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Tests\Asset;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    protected function setUp()
    {
        parent::setUp();

        $this->assetPathResolver = $this->createMock(AssetPathResolverInterface::class);
        $this->innerPackage = $this->createMock(PackageInterface::class);
        $this->configResolver = $this->createMock(ConfigResolverInterface::class);
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
        $this->configResolver
            ->method('getParameter')
            ->with('design')
            ->willReturn($currentDesign);

        $package = new ThemePackage($this->assetPathResolver, $this->innerPackage);
        $package->setConfigResolver($this->configResolver);
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
        $this->configResolver
            ->method('getParameter')
            ->with('design')
            ->willReturn($currentDesign);

        $package = new ThemePackage($this->assetPathResolver, $this->innerPackage);
        $package->setConfigResolver($this->configResolver);
        self::assertSame($version, $package->getVersion($assetPath));
    }
}
