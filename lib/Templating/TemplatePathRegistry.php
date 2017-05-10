<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngine\Templating;

use Serializable;

class TemplatePathRegistry implements TemplatePathRegistryInterface, Serializable
{
    private $pathMapping = [];

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
        $this->pathMapping[$templateName] = str_replace(dirname($this->kernelRootDir).'/', '', $path);
    }

    public function getTemplatePath($templateName)
    {
        return isset($this->pathMapping[$templateName]) ? $this->pathMapping[$templateName] : $templateName;
    }

    public function serialize()
    {
        return serialize([$this->pathMapping, $this->kernelRootDir]);
    }

    public function unserialize($serialized)
    {
        list($this->pathMapping, $this->kernelRootDir) = unserialize($serialized);
    }
}
