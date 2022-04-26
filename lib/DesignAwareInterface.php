<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine;

interface DesignAwareInterface
{
    /**
     * Returns current design.
     *
     * @return string
     */
    public function getCurrentDesign();
}
