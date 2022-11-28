<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Templating;

use Serializable;

class TemplatePathRegistry implements TemplatePathRegistryInterface, Serializable
{
    /** @var array<string, string> */
    private $pathMap = [];

    /** @var string */
    private $kernelRootDir;

    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    public function mapTemplatePath($templateName, $path)
    {
        $this->pathMap[$templateName] = str_replace($this->kernelRootDir . '/', '', $path);
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
        [$this->pathMap, $this->kernelRootDir] = unserialize($serialized);
    }

    public function __serialize(): array
    {
        return [$this->pathMap, $this->kernelRootDir];
    }

    public function __unserialize(array $data): void
    {
        [$this->pathMap, $this->kernelRootDir] = $data;
    }
}
