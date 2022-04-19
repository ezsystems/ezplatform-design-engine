<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Templating;

use eZ\Publish\Core\MVC\ConfigResolverInterface;

class ThemeTemplateNameResolver implements TemplateNameResolverInterface
{
    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    /**
     * Collection of already resolved template names.
     *
     * @var array
     */
    private $resolvedTemplateNames = [];

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * Returns the name of the current design, in the current context (i.e. SiteAccess).
     *
     * @return string
     */
    private function getCurrentDesign()
    {
        return $this->configResolver->getParameter('design');
    }

    public function resolveTemplateName($name)
    {
        if (!$this->isTemplateDesignNamespaced($name)) {
            return $name;
        } elseif (isset($this->resolvedTemplateNames[$name])) {
            return $this->resolvedTemplateNames[$name];
        }

        return $this->resolvedTemplateNames[$name] = str_replace('@' . self::EZ_DESIGN_NAMESPACE, '@' . $this->getCurrentDesign(), $name);
    }

    public function isTemplateDesignNamespaced($name)
    {
        return (strpos($name, '@' . self::EZ_DESIGN_NAMESPACE) !== false) || (strpos($name, '@' . $this->getCurrentDesign()) !== false);
    }
}
