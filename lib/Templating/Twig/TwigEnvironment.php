<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngine\Templating\Twig;

use EzSystems\EzPlatformDesignEngine\Templating\TemplateNameResolverInterface;
use Twig_Environment;
use Twig_Source;

class TwigEnvironment extends Twig_Environment
{
    /**
     * @var TemplateNameResolverInterface
     */
    private $templateNameResolver;

    private $kernelRootDir;

    public function compileSource(Twig_Source $source)
    {
        $this->addPathMapping($source);

        return parent::compileSource($source);
    }

    public function setTemplateNameResolver(TemplateNameResolverInterface $templateNameResolver)
    {
        $this->templateNameResolver = $templateNameResolver;
    }

    public function setKernelRootDir($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    public function addPathMapping($source)
    {
        if (!($this->isDebug() && $source instanceof Twig_Source)) {
            return;
        }

        if ($this->templateNameResolver->isTemplateDesignNamespaced($source->getName())) {
            DebugTemplate::addPathMapping(
                $source->getName(),
                str_replace(dirname($this->kernelRootDir).'/', '', $source->getPath())
            );
        }
    }
}
