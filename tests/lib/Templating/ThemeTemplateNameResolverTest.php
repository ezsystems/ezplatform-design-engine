<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngine\Tests\Templating;

use EzSystems\EzPlatformDesignEngine\Templating\ThemeTemplateNameResolver;
use PHPUnit\Framework\TestCase;

class ThemeTemplateNameResolverTest extends TestCase
{
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
        $resolver = new ThemeTemplateNameResolver($currentDesign);
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
        $resolver = new ThemeTemplateNameResolver($currentDesign);
        self::assertSame($expected, $resolver->isTemplateDesignNamespaced($templateName));
    }
}
