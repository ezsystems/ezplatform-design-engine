# EzPlatformDesignEngine documentation

This features allows you to provide design themes to your eZ application, with automatic fallback system.
It is very similar to [eZ Publish legacy design fallback system](https://doc.ez.no/eZ-Publish/Technical-manual/5.x/Concepts-and-basics/Designs/Design-combinations).

When you call a given template or asset, the system will look for it in the first configured theme. 
If it cannot be found, the system will fallback to all other configured themes for your current SiteAccess.

Under the hood, theming system uses Twig namespaces. As such, Twig is the only supported template engine.
For assets, it uses Symfony Asset component with asset packages.

## Terminology
* **Theme**: Labeled collection of templates and assets.<br>
  Typically a directory containing templates. For example, templates located under `app/Resources/views/themes/my_theme`
  or `src/AppBundle/Resources/views/themes/my_theme` are part of `my_theme` theme.
* **Design**: Collection of themes.<br>
  The order of themes within a design is important as it defines the fallback order.
  A design is identified with a name. One design can be used by SiteAccess.

## Configuration
To define and use a design, you need to:

0. Declare it, with a name and a collection of themes to use
0. Use it for your SiteAccess

Here is a simple example:
```yaml
ezpublish:
    design:
        # You declare every available designs under "list".
        list:
            # my_design will be composed of "theme1" and "theme2"
            # "theme1" will be the first tried. If the template cannot be found in "theme1", "theme2" will be tried out.
            my_design: [theme1, theme2]
    system:
        my_siteaccess:
            # my_siteaccess will use "my_design"
            design: my_design
```

## Usage
* [Usage with templates](templates.md)
* [Usage with assets](assets.md)
