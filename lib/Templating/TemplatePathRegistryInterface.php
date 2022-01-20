<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Templating;

/**
 * Registry to map templates logical names and their real path.
 * Mainly used for profiling.
 */
interface TemplatePathRegistryInterface
{
    /**
     * Adds a template path mapping to the registry.
     *
     * @param string $templateName The template logical name
     * @param string $path         Template path
     */
    public function mapTemplatePath($templateName, $path);

    /**
     * Returns path for given template.
     *
     * @param string $templateName
     *
     * @return string
     */
    public function getTemplatePath($templateName);

    /**
     * Returns the whole hash map.
     *
     * @return array
     */
    public function getPathMap();
}
