# Ibexa Design Engine

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9423927d-ce0f-4cc2-b521-287006608f10/mini.png)](https://insight.sensiolabs.com/projects/9423927d-ce0f-4cc2-b521-287006608f10)
[![Build Status](https://img.shields.io/travis/ezsystems/ezplatform-design-engine.svg?style=flat-square&branch=master)](https://travis-ci.org/ezsystems/ezplatform-design-engine)
[![Downloads](https://img.shields.io/packagist/dt/ezsystems/ezplatform-design-engine.svg?style=flat-square)](https://packagist.org/packages/ezsystems/ezplatform-design-engine)
[![Latest release](https://img.shields.io/github/release/ezsystems/ezplatform-design-engine.svg?style=flat-square)](https://github.com/ezsystems/ezplatform-design-engine/releases)
[![License](https://img.shields.io/github/license/ezsystems/ezplatform-design-engine.svg?style=flat-square)](LICENSE)

Design fallback system for Ibexa similar to
[legacy design fallback system](https://doc.ez.no/eZ-Publish/Technical-manual/5.x/Concepts-and-basics/Designs/Design-combinations).

Lets you define fallback order for templates and assets.

## Requirements
Ibexa Design engine works with [Ibexa DXP](https://www.ibexa.co/products) and Ibexa Open Source v3.x.

## Installation
This bundle is available on [Packagist](https://packagist.org/packages/ezsystems/ez-platform-design-engine).
You can install it using Composer.

```
composer require ezsystems/ezplatform-design-engine
```

Then enable it in your project:

```php
// config/bundles.php

return [
    // ...
    EzSystems\EzPlatformDesignEngineBundle\EzPlatformDesignEngineBundle::class => ['all' => true],
    // ...
];

```

## Documentation
See [doc/](doc)

## COPYRIGHT
Copyright (C) 1999-2021 Ibexa AS (formerly eZ Systems AS). All rights reserved.

## LICENSE
This source code is available separately under the following licenses:

A - Ibexa Business Use License Agreement (Ibexa BUL),
version 2.4 or later versions (as license terms may be updated from time to time)
Ibexa BUL is granted by having a valid Ibexa DXP (formerly eZ Platform Enterprise) subscription,
as described at: https://www.ibexa.co/product
For the full Ibexa BUL license text, please see:
https://www.ibexa.co/software-information/licenses-and-agreements (latest version applies)

AND

B - GNU General Public License, version 2
Grants an copyleft open source license with ABSOLUTELY NO WARRANTY. For the full GPL license text, please see:
https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
