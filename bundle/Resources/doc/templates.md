# Design usage with templates

By convention, a theme directory must be located under `<bundle_directory>/Resources/views/themes/` or global
`app/Resources/views/themes/` directories.

Typical paths can be for example:
* `app/Resources/views/themes/foo/` => Templates will be part of `foo` theme.
* `app/Resources/views/themes/bar/` => Templates will be part of `bar` theme.
* `src/AppBundle/Resources/views/themes/foo/` => Templates will be part of `foo`theme.
* `src/Acme/TestBundle/Resources/views/themes/the_best/` => Templates will be part of `the_best` theme.

In order to use the configured design with templates, you need to use **`@ezdesign`** special **Twig namespace**.

```jinja
{# Will load 'some_template.html.twig' directly under one of the specified themes directories #}
{{ include("@ezdesign/some_template.html.twig") }}

{# Will load 'another_template.html.twig', located under 'full/' directory, which is located under one of the specified themes directories #}
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
Default fallback order is the following:
* Application view directory: `app/Resources/views/`
* Application theme directory: `app/Resources/views/themes/<theme_name>/`
* Bundle theme directory: `src/<bundle_directory>/Resources/views/themes/<theme_name>/`

> Bundle fallback order is the instantiation order in `AppKernel`.

### Additional override paths
It is possible to add addition global override directories, similar to `app/Resources/views/`.

```yaml
ezpublish:
    design:
        template_override_paths:
            - "%kernel.root_dir%/another_override_directory"
            - "/some/other/directory"
```

> `app/Resources/views/` will **always** be the top level override directory.

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
ezpublish:
    design:
        phpstorm:
    
            # Activates PHPStorm support
            enabled:              '%kernel.debug%'
    
            # Path where to store PHPStorm configuration file for additional Twig namespaces (ide-twig.json).
            twig_config_path:     '%kernel.root_dir%/..'
```
