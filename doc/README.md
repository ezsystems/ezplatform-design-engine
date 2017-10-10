# EzPlatformDesignEngine documentation

This feature enables you to provide design themes for your eZ application, with an automatic fallback system.
It is very similar to the [eZ Publish legacy design fallback system](https://doc.ez.no/eZ-Publish/Technical-manual/5.x/Concepts-and-basics/Designs/Design-combinations).

When you call a given template or asset, the system will look for it in the first configured theme.
If it cannot be found, the system will fall back to all other configured themes for your current SiteAccess.

Under the hood, the theming system uses Twig namespaces. As such, Twig is the only supported template engine.
For assets, the system uses the Symfony Asset component with asset packages.

## Terminology

- **Theme**: Labeled collection of templates and assets.

  Typically a directory containing templates. For example, templates located under `app/Resources/views/themes/my_theme`
  or `src/AppBundle/Resources/views/themes/my_theme` are part of `my_theme` theme.
- **Design**: Collection of themes.

  The order of themes within a design is important as it defines the fallback order.
  A design is identified with a name. One design can be used per SiteAccess.

## Configuration

To define and use a design, you need to:

1. Declare it, with a name and a collection of themes to use
1. Use it for your SiteAccess

Here is a simple example:

```yaml
# ezplatform.yml
ezdesign:
    # You declare all available designs under "design_list".
    design_list:
        # my_design will be composed of "theme1" and "theme2"
        # "theme1" will be tried first. If the template cannot be found in "theme1", "theme2" will be tried out.
        my_design: [theme1, theme2]

ezpublish:
    # ...
    system:
        my_siteaccess:
            # my_siteaccess will use "my_design"
            design: my_design
```

> **Note**: Default design for a SiteAccess is `standard` which contains no themes.
> If you use the `@ezdesign` Twig namespace and/or the `ezdesign` asset package, the system will always fall back to
> application level and override directories for templates/assets lookup.

## Usage

- [Usage with templates](templates.md)
- [Usage with assets](assets.md)

## Referencing current design

It is possible to reference current design in order to inject it into a service.
To do so, you just need to reference the `$design$` dynamic setting:

```yaml
services:
    my_service:
        class: Foo\Bar
        arguments: ["$design$"]
```

It is also possible to use the `ConfigResolver` service (`ezpublish.config.resolver`):

```php
// In a controller
$currentDesign = $this->getConfigResolver->getParameter('design');
```
