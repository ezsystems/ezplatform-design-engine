<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Tests\Templating;

use EzSystems\EzPlatformDesignEngine\Templating\TemplatePathRegistry;
use PHPUnit\Framework\TestCase;

class TemplatePathRegistryTest extends TestCase
{
    private function getExpectedRelativePath($templateFullPath, $kernelRootDir)
    {
        return str_replace(dirname($kernelRootDir) . '/', '', $templateFullPath);
    }

    public function testMapTemplatePath()
    {
        $kernelRootDir = __DIR__;
        $templateLogicalName = '@foo/bar.html.twig';
        $templateFullPath = $kernelRootDir . '/' . $templateLogicalName;

        $registry = new TemplatePathRegistry($kernelRootDir);
        self::assertSame([], $registry->getPathMap());
        $registry->mapTemplatePath($templateLogicalName, $templateFullPath);
        self::assertSame(
            [$templateLogicalName => $this->getExpectedRelativePath($templateFullPath, $kernelRootDir)],
            $registry->getPathMap()
        );
    }

    public function testGetTemplatePath()
    {
        $kernelRootDir = __DIR__;
        $templateLogicalName = '@foo/bar.html.twig';
        $templateFullPath = $kernelRootDir . '/' . $templateLogicalName;

        $registry = new TemplatePathRegistry($kernelRootDir);
        $registry->mapTemplatePath($templateLogicalName, $templateFullPath);
        self::assertSame(
            $this->getExpectedRelativePath($templateFullPath, $kernelRootDir),
            $registry->getTemplatePath($templateLogicalName)
        );
    }

    public function testGetTemplatePathNotMapped()
    {
        $kernelRootDir = __DIR__;
        $templateLogicalName = '@foo/bar.html.twig';

        $registry = new TemplatePathRegistry($kernelRootDir);
        self::assertSame($templateLogicalName, $registry->getTemplatePath($templateLogicalName));
    }
}
