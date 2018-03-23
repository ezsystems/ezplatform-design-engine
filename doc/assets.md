# Design usage with assets

For assets, a special **`ezdesign` asset package** is available.

```jinja
<script src="{{ asset("js/foo.js", "ezdesign") }}"></script>

<link rel="stylesheet" href="{{ asset("css/foo.css", "ezdesign") }}" media="screen" />

<img src="{{ asset("images/foo.png", "ezdesign") }}" alt="foo"/>
```

Using the `ezdesign` package will resolve current design with theme fallback.

By convention, an asset theme directory can be located in:
- `<bundle_directory>/Resources/public/themes/`
- `web/assets/themes/`

Typical paths can be for example:
- `<bundle_directory>/Resources/public/themes/foo/` => Assets will be part of the `foo` theme.
- `<bundle_directory>/Resources/public/themes/bar/` => Assets will be part of the `bar` theme.
- `web/assets/themes/biz/` => Assets will be part of the `biz` theme.

It is also possible to use `web/assets` as a global override directory.
If called asset is present **directly under this directory**, it will always be considered first.

> **Important**: You must have *installed* your assets with `assets:install` command, so that your public resources are
*installed* into the `web/` directory.

## Fallback order

The default fallback order is:
- Application assets directory: `web/assets/`
- Application theme directory: `web/assets/themes/<theme_name>/`
- Bundle theme directory: `web/bundles/<bundle_directory>/themes/<theme_name>/`

Calling `asset("js/foo.js", "ezdesign")` can for example be resolved to `web/bundles/app/themes/my_theme/js/foo.js`.

## Performance and asset resolution

When using themes, paths for assets are resolved at runtime.
This is due to how the Symfony Asset component is integrated with Twig.
This can cause significant performance impact because of I/O calls when looping over all potential theme directories,
especially when using a lot of different designs and themes.

To work around this issue, assets resolution can be provisioned at compilation time.
Provisioning is the **default behavior in non-debug mode** (e.g. `prod` environment).
In debug mode (e.g. `dev` environment), assets are being resolved at runtime.

This behavior can however be controlled by the `disable_assets_pre_resolution` setting.

```yaml
# ezplatform_prod.yml
ezdesign:
    # Force runtime resolution
    # Default value is '%kernel.debug%'
    disable_assets_pre_resolution: true
```
