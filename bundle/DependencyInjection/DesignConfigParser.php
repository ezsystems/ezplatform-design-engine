<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngineBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ParserInterface;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class DesignConfigParser implements ParserInterface
{
    public function mapConfig(array &$scopeSettings, $currentScope, ContextualizerInterface $contextualizer)
    {
        if (isset($scopeSettings['design'])) {
            $contextualizer->setContextualParameter('design', $currentScope, $scopeSettings['design']);
        }
    }

    public function preMap(array $config, ContextualizerInterface $contextualizer)
    {
    }

    public function postMap(array $config, ContextualizerInterface $contextualizer)
    {
    }

    public function addSemanticConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->scalarNode('design')
                ->cannotBeEmpty()
                ->info('Name of the design to use. Must be declared in ezdesign.design_list')
            ->end();
    }
}
