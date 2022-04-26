<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Tests\Asset;

use EzSystems\EzPlatformDesignEngine\Asset\AssetPathResolver;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AssetPathResolverTest extends TestCase
{
    public function testResolveAssetPathFail()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('warning');

        $resolver = new AssetPathResolver(['foo' => []], __DIR__, $logger);
        $assetPath = 'images/foo.png';
        self::assertSame($assetPath, $resolver->resolveAssetPath($assetPath, 'foo'));
    }

    /**
     * @expectedException \EzSystems\EzPlatformDesignEngine\Exception\InvalidDesignException
     */
    public function testResolveInvalidDesign()
    {
        $resolver = new AssetPathResolver([], __DIR__);
        $assetPath = 'images/foo.png';
        self::assertSame($assetPath, $resolver->resolveAssetPath($assetPath, 'foo'));
    }

    public function resolveAssetPathProvider()
    {
        return [
            [
                [
                    'foo' => [
                        'themes/theme1',
                        'themes/theme2',
                        'themes/theme3',
                    ],
                ],
                ['themes/theme2', 'themes/theme3'],
                'images/foo.png',
                'themes/theme2/images/foo.png',
            ],
            [
                [
                    'foo' => [
                        'themes/theme1',
                        'themes/theme2',
                        'themes/theme3',
                    ],
                ],
                ['themes/theme2'],
                'images/foo.png',
                'themes/theme2/images/foo.png',
            ],
            [
                [
                    'foo' => [
                        'themes/theme1',
                        'themes/theme2',
                        'themes/theme3',
                    ],
                ],
                ['themes/theme1', 'themes/theme2', 'themes/theme3'],
                'images/foo.png',
                'themes/theme1/images/foo.png',
            ],
            [
                [
                    'foo' => [
                        'themes/theme1',
                        'themes/theme2',
                        'themes/theme3',
                    ],
                ],
                ['themes/theme3'],
                'images/foo.png',
                'themes/theme3/images/foo.png',
            ],
            [
                [
                    'foo' => [
                        'themes/theme1',
                        'themes/theme2',
                        'themes/theme3',
                    ],
                ],
                [],
                'images/foo.png',
                'images/foo.png',
            ],
        ];
    }

    /**
     * @dataProvider resolveAssetPathProvider
     */
    public function testResolveAssetPath(array $designPaths, array $existingPaths, $path, $resolvedPath)
    {
        $webrootDir = vfsStream::setup('web');
        foreach ($designPaths['foo'] as $designPath) {
            if (in_array($designPath, $existingPaths)) {
                $fileInfo = new \SplFileInfo($designPath . '/' . $path);
                $parent = $webrootDir;
                foreach (explode('/', $fileInfo->getPath()) as $dir) {
                    if (!$parent->hasChild($dir)) {
                        $directory = vfsStream::newDirectory($dir)->at($parent);
                    } else {
                        $directory = $parent->getChild($dir);
                    }

                    $parent = $directory;
                }

                vfsStream::newFile($fileInfo->getFilename())->at($parent)->setContent('Vive le sucre !!!');
            }
        }

        $resolver = new AssetPathResolver($designPaths, $webrootDir->url());
        self::assertSame($resolvedPath, $resolver->resolveAssetPath($path, 'foo'));
    }
}
