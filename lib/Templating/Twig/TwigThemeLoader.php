<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformDesignEngine\Templating\Twig;

use EzSystems\EzPlatformDesignEngine\Templating\TemplateNameResolverInterface;
use EzSystems\EzPlatformDesignEngine\Templating\TemplatePathRegistryInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Twig\Source;

/**
 * Decorates regular Twig FilesystemLoader.
 * It resolves generic @ezdesign namespace to the actual current namespace.
 */
class TwigThemeLoader implements LoaderInterface
{
    /**
     * @var \EzSystems\EzPlatformDesignEngine\Templating\TemplateNameResolverInterface
     */
    private $nameResolver;

    /**
     * @var \EzSystems\EzPlatformDesignEngine\Templating\TemplatePathRegistryInterface
     */
    private $pathRegistry;

    /**
     * @var \Twig\Loader\FilesystemLoader
     */
    private $innerFilesystemLoader;

    public function __construct(
        TemplateNameResolverInterface $templateNameResolver,
        TemplatePathRegistryInterface $templatePathRegistry,
        LoaderInterface $innerFilesystemLoader
    ) {
        $this->innerFilesystemLoader = $innerFilesystemLoader;
        $this->nameResolver = $templateNameResolver;
        $this->pathRegistry = $templatePathRegistry;
    }

    public function exists($name)
    {
        return $this->innerFilesystemLoader->exists($this->nameResolver->resolveTemplateName($name));
    }

    public function getSource($name)
    {
        return $this->innerFilesystemLoader->getSource($this->nameResolver->resolveTemplateName($name));
    }

    public function getSourceContext(string $name): Source
    {
        $source = $this->innerFilesystemLoader->getSourceContext($this->nameResolver->resolveTemplateName((string)$name));
        $this->pathRegistry->mapTemplatePath($source->getName(), $source->getPath());

        return $source;
    }

    public function getCacheKey(string $name): string
    {
        return $this->innerFilesystemLoader->getCacheKey($this->nameResolver->resolveTemplateName($name));
    }

    public function isFresh(string $name, int $time): bool
    {
        return $this->innerFilesystemLoader->isFresh($this->nameResolver->resolveTemplateName($name), $time);
    }

    public function getPaths($namespace = FilesystemLoader::MAIN_NAMESPACE)
    {
        return $this->innerFilesystemLoader->getPaths($namespace);
    }

    public function getNamespaces()
    {
        return $this->innerFilesystemLoader->getNamespaces();
    }

    public function setPaths($paths, $namespace = FilesystemLoader::MAIN_NAMESPACE)
    {
        $this->innerFilesystemLoader->setPaths($paths, $namespace);
    }

    public function addPath($path, $namespace = FilesystemLoader::MAIN_NAMESPACE)
    {
        $this->innerFilesystemLoader->addPath($path, $namespace);
    }

    public function prependPath($path, $namespace = FilesystemLoader::MAIN_NAMESPACE)
    {
        $this->innerFilesystemLoader->prependPath($path, $namespace);
    }
}
