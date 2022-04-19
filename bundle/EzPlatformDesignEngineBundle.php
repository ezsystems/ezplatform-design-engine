<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngineBundle;

use EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\Compiler\AssetPathResolutionPass;
use EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\Compiler\AssetThemePass;
use EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\Compiler\PHPStormPass;
use EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\Compiler\TwigThemePass;
use EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\DesignConfigParser;
use EzSystems\EzPlatformDesignEngineBundle\DependencyInjection\EzPlatformDesignEngineExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzPlatformDesignEngineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension $eZExtension */
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addConfigParser(new DesignConfigParser());
        $eZExtension->addDefaultSettings(__DIR__ . '/Resources/config', ['default_settings.yml']);

        $container->addCompilerPass(new TwigThemePass());
        $container->addCompilerPass(new AssetThemePass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new AssetPathResolutionPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new PHPStormPass(), PassConfig::TYPE_OPTIMIZE);
    }

    public function getContainerExtension()
    {
        if (!isset($this->extension)) {
            $this->extension = new EzPlatformDesignEngineExtension();
        }

        return $this->extension;
    }
}
