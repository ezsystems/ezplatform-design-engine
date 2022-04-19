<?php

return EzSystems\EzPlatformCodeStyle\PhpCsFixer\EzPlatformInternalConfigFactory::build()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . '/lib',
                __DIR__ . '/bundle',
                __DIR__ . '/tests',
            ])
            ->files()->name('*.php')
    );
