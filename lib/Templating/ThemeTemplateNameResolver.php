<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngine\Templating;

class ThemeTemplateNameResolver implements TemplateNameResolverInterface
{
    /**
     * @var string Name of the current design, in the current context (e.g. SiteAccess)
     */
    private $currentDesign;

    /**
     * Collection of already resolved template names.
     *
     * @var array
     */
    private $resolvedTemplateNames = [];

    public function __construct($currentDesign)
    {
        $this->currentDesign = $currentDesign;
    }

    public function resolveTemplateName($name)
    {
        if (!$this->isTemplateDesignNamespaced($name)) {
            return $name;
        } elseif (isset($this->resolvedTemplateNames[$name])) {
            return $this->resolvedTemplateNames[$name];
        }

        return $this->resolvedTemplateNames[$name] = str_replace('@' . self::EZ_DESIGN_NAMESPACE, '@' . $this->currentDesign, $name);
    }

    public function isTemplateDesignNamespaced($name)
    {
        return (strpos($name, '@' . self::EZ_DESIGN_NAMESPACE) !== false) || (strpos($name, '@' . $this->currentDesign) !== false);
    }
}
