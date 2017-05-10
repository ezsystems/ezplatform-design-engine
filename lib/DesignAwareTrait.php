<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngine;

trait DesignAwareTrait
{
    /**
     * @var string
     */
    protected $currentDesign;

    /**
     * Injects the current design.
     *
     * @param string $currentDesign
     */
    public function setCurrentDesign($currentDesign)
    {
        $this->currentDesign = $currentDesign;
    }
}
