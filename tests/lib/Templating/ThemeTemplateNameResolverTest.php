<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Tests\Templating;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\EzPlatformDesignEngine\Templating\ThemeTemplateNameResolver;
use PHPUnit\Framework\TestCase;

class ThemeTemplateNameResolverTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    public function setUp()
    {
        parent::setUp();

        $this->configResolver = $this->createMock(ConfigResolverInterface::class);
    }

    public function templateNameProvider()
    {
        return [
            [null, 'foo.html.twig', 'foo.html.twig'],
            ['my_design', '@ezdesign/foo.html.twig', '@my_design/foo.html.twig'],
            ['my_design', '@AcmeTest/foo.html.twig', '@AcmeTest/foo.html.twig'],
        ];
    }

    /**
     * @dataProvider templateNameProvider
     */
    public function testResolveTemplateName($currentDesign, $templateName, $expectedTemplateName)
    {
        $this->configResolver
            ->method('getParameter')
            ->with('design')
            ->willReturn($currentDesign);
        $resolver = new ThemeTemplateNameResolver($this->configResolver);
        self::assertSame($expectedTemplateName, $resolver->resolveTemplateName($templateName));
    }

    public function isTemplateDesignNamespacedProvider()
    {
        return [
            [null, 'foo.html.twig', false],
            ['my_design', '@ezdesign/foo.html.twig', true],
            ['my_design', '@my_design/foo.html.twig', true],
            ['my_design', '@AcmeTest/foo.html.twig', false],
        ];
    }

    /**
     * @dataProvider isTemplateDesignNamespacedProvider
     */
    public function testIsTemplateDesignNamespaced($currentDesign, $templateName, $expected)
    {
        $this->configResolver
            ->method('getParameter')
            ->with('design')
            ->willReturn($currentDesign);
        $resolver = new ThemeTemplateNameResolver($this->configResolver);
        self::assertSame($expected, $resolver->isTemplateDesignNamespaced($templateName));
    }
}
