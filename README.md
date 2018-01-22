# EzPlatformDesignEngine

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9423927d-ce0f-4cc2-b521-287006608f10/mini.png)](https://insight.sensiolabs.com/projects/9423927d-ce0f-4cc2-b521-287006608f10)
[![Build Status](https://img.shields.io/travis/ezsystems/ezplatform-design-engine.svg?style=flat-square&branch=master)](https://travis-ci.org/ezsystems/ezplatform-design-engine)
[![Downloads](https://img.shields.io/packagist/dt/ezsystems/ezplatform-design-engine.svg?style=flat-square)](https://packagist.org/packages/ezsystems/ezplatform-design-engine)
[![Latest release](https://img.shields.io/github/release/ezsystems/ezplatform-design-engine.svg?style=flat-square)](https://github.com/ezsystems/ezplatform-design-engine/releases)
[![License](https://img.shields.io/github/license/ezsystems/ezplatform-design-engine.svg?style=flat-square)](LICENSE)

Design fallback system for eZ Platform similar to
[legacy design fallback system](https://doc.ez.no/eZ-Publish/Technical-manual/5.x/Concepts-and-basics/Designs/Design-combinations).

Lets you define fallback order for templates and assets.

## Requirements
EzPlatformDesignEngine works with eZ Platfom 1.x (kernel version >=6.0).

## Installation
This bundle is available on [Packagist](https://packagist.org/packages/ezsystems/ez-platform-design-engine).
You can install it using Composer.

```
composer require ezsystems/ezplatform-design-engine
```

Then add it to your application:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new EzSystems\EzPlatformDesignEngineBundle\EzPlatformDesignEngineBundle,
        // ...
    ];
}
```

## Documentation
See [doc/](doc)

