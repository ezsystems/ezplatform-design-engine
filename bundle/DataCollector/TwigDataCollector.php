<?php

/*
 * This file is part of the EzPlatformDesignEngine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformDesignEngineBundle\DataCollector;

use EzSystems\EzPlatformDesignEngine\Templating\TemplateNameResolverInterface;
use Symfony\Bridge\Twig\DataCollector\TwigDataCollector as BaseCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;

class TwigDataCollector extends BaseCollector implements LateDataCollectorInterface
{
    /**
     * @var TemplateNameResolverInterface
     */
    private $templateNameResolver;

    public function __construct(\Twig_Profiler_Profile $profile, TemplateNameResolverInterface $templateNameResolver)
    {
        parent::__construct($profile);
        $this->templateNameResolver = $templateNameResolver;
    }

    private function getTemplateNameResolver()
    {
        if (!isset($this->templateNameResolver)) {
            $this->templateNameResolver = unserialize($this->data['template_name_resolver']);
        }

        return $this->templateNameResolver;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        parent::collect($request, $response, $exception);
    }

    public function lateCollect()
    {
        parent::lateCollect();
        $this->data['template_name_resolver'] = serialize($this->templateNameResolver);
    }

    public function getTime()
    {
        return parent::getTime();
    }

    public function getTemplateCount()
    {
        return parent::getTemplateCount();
    }

    public function getTemplates()
    {
        $resolver = $this->getTemplateNameResolver();
        $templates = parent::getTemplates();
        return $templates;
    }

    public function getBlockCount()
    {
        return parent::getBlockCount();
    }

    public function getMacroCount()
    {
        return parent::getMacroCount();
    }

    public function getHtmlCallGraph()
    {
        return parent::getHtmlCallGraph();
    }

    public function getProfile()
    {
        return parent::getProfile();
    }
}
