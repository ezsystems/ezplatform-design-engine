# Design usage with templates

By convention, a theme directory must be located under `<bundle_directory>/Resources/views/themes/` or global
`app/Resources/views/themes/` directories.

Typical paths can be for example:
- `app/Resources/views/themes/foo/` => Templates will be part of the `foo` theme.
- `app/Resources/views/themes/bar/` => Templates will be part of the `bar` theme.
- `src/AppBundle/Resources/views/themes/foo/` => Templates will be part of the `foo`theme.
- `src/Acme/TestBundle/Resources/views/themes/the_best/` => Templates will be part of `the_best` theme.

In order to use the configured design with templates, you need to use **`@ezdesign`** special **Twig namespace**.

```jinja
{# Will load 'some_template.html.twig' directly under one of the specified theme directories #}
{{ include("@ezdesign/some_template.html.twig") }}

{# Will load 'another_template.html.twig', located under 'full/' directory, which is located under one of the specified theme directories #}
{{ include("@ezdesign/full/another_template.html.twig") }}
```

You can also use `@ezdesign` notation in your eZ template selection rules:

```yaml
ezpublish:
    system:
        my_siteaccess:
            content_view:
                full:
                    home:
                        template: "@ezdesign/full/home.html.twig"
```

> You may also use this notation in controllers.

## Fallback order

The default fallback order is:
- Application theme directory: `app/Resources/views/themes/<theme_name>/`
- Bundle theme directory: `src/<bundle_directory>/Resources/views/themes/<theme_name>/`

Prior to version 2.0 of this package, `app/Resources/views` was the top-level global override directory.
This behavior is not recommended as it could affect both core features and third party bundles 
which already use `@ezdesign`. However, if still needed, it can be achieved by the following configuration:

```yaml
ezdesign:
    templates_override_paths:
        - '%kernel.root_dir%/app/Resources/views'
```

> Bundle fallback order is the instantiation order in `AppKernel`.

### Additional theme paths

In addition to the convention described above, it is also possible to add arbitrary Twig template directories to a theme
from configuration. This can be useful when you want to define templates from third-party bundles as part of one of your
themes, or when upgrading your application in order to use eZ Platform design engine, when your existing templates
are not yet following the convention.

```yaml
ezdesign:
    design_list:
        my_design: [my_theme, some_other_theme]
    templates_theme_paths:
        # FOSUserBundle templates will be part of "my_theme" theme
        my_theme:
            - '%kernel.root_dir%/../vendor/friendsofsymfony/user-bundle/Resources/views'
```

> **Paths precedence**: Directories following the convention will **always** have precedence over the ones defined
> in config. This ensures that it is always possible to override a template from the application.

### Additional override paths

It is possible to add additional global override directories.

```yaml
ezdesign:
    templates_override_paths:
        - "%kernel.root_dir%/another_override_directory"
        - "/some/other/directory"
```

## PHPStorm support

`@ezdesign` Twig namespace is a *virtual* namespace, and as such is not automatically recognized by PHPStorm Symfony plugin
for `goto` actions.

`EzPlatformDesignEngine` will generate a `ide-twig.json` file which will contain all detected theme paths for templates in your project.
It is activated by default in debug mode (`%kernel.debug%`).

By default, this config file will be stored at your project root (`%kernel.root_dir%/..`), but you can customize the path
if your PHPStorm project root doesn't match your Symfony project root.

> Note: `ide-twig.json` **must** be stored at your PHPStorm project root.

Default config:
```yaml
ezdesign:
    phpstorm:

        # Activates PHPStorm support
        enabled:              '%kernel.debug%'

        # Path where to store PHPStorm configuration file for additional Twig namespaces (ide-twig.json).
        twig_config_path:     '%kernel.root_dir%/..'
```
