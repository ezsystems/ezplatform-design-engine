# Upgrading eZ Design Engine to v2.0

## Backward incompatible changes

### Global override directory

The `app/Resources/views` project directory is no longer a global override directory for all 
Designs and Themes. Using it is not recommended as it can lead to accidental overriding of core or 
third party bundle templates which also use `@ezdesign` and share the same name.

However, if still needed, it can be achieved by the following configuration:

```yaml
ezdesign:
    templates_override_paths:
        - '%kernel.root_dir%/app/Resources/views'
```
