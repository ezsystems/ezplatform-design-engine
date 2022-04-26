<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Templating;

use Serializable;

class TemplatePathRegistry implements TemplatePathRegistryInterface, Serializable
{
    private $pathMap = [];

    /**
     * @var
     */
    private $kernelRootDir;

    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    public function mapTemplatePath($templateName, $path)
    {
        $this->pathMap[$templateName] = str_replace(dirname($this->kernelRootDir) . '/', '', $path);
    }

    public function getTemplatePath($templateName)
    {
        return isset($this->pathMap[$templateName]) ? $this->pathMap[$templateName] : $templateName;
    }

    public function getPathMap()
    {
        return $this->pathMap;
    }

    public function serialize()
    {
        return serialize([$this->pathMap, $this->kernelRootDir]);
    }

    public function unserialize($serialized)
    {
        list($this->pathMap, $this->kernelRootDir) = unserialize($serialized);
    }
}
