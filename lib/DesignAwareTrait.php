<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine;

use eZ\Publish\Core\MVC\ConfigResolverInterface;

trait DesignAwareTrait
{
    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    public function setConfigResolver(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * Returns the current design.
     *
     * @return string
     */
    public function getCurrentDesign()
    {
        return $this->configResolver->getParameter('design');
    }
}
