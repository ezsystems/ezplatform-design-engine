<?php
use EzSystems\EzPlatformCodeStyle\PhpCsFixer\EzPlatformInternalConfigFactory;

$configFactory = new EzPlatformInternalConfigFactory();
$configFactory->withRules([
    'declare_strict_types' => false,
]);

return $configFactory->buildConfig()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . '/lib',
                __DIR__ . '/bundle',
                __DIR__ . '/tests',
            ])
            ->files()->name('*.php')
    );
