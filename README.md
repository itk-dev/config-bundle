# Settings

App config based on [CraueConfigBundle](https://github.com/craue/CraueConfigBundle) and [EasyAdmin](https://symfony.com/bundles/EasyAdminBundle/).

## Installation

```shell
composer require itk-dev/settings-bundle "^1.0"
```

## Configuration

```yaml
# config/packages/craue_config.yaml
# Cf. https://github.com/craue/CraueConfigBundle?tab=readme-ov-file#using-a-custom-entity-for-settings
craue_config:
    entity_name: ItkDev\ConfigBundle\Entity\Setting

# Optionally, enable caching for craue/config-bundle (cf. https://github.com/craue/CraueConfigBundle/#enable-caching-optional)
services:
  craue_config_cache_provider:
    class: Symfony\Component\Cache\Adapter\FilesystemAdapter
    public: false
    arguments:
      - 'craue_config'
      - 0
      - '%kernel.cache_dir%'
```

Depending on your doctrine setup, you may have to add `ItkDevConfigBundle` to your doctrine mappings, e.g.:

```yaml
# config/packages/doctrine.yaml
doctrine:
    orm:
        entity_managers:
            default:
                mappings:
                    # …

                    ItkDevConfigBundle: ~
```

If using [Doctrine
migrations](https://github.com/doctrine/DoctrineMigrationsBundle), you
should [filter out the `craue_config_setting` table from
migrations](https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html#manual-tables):

```yaml
# config/packages/doctrine.yaml
doctrine:
    dbal:
        # …
        schema_filter: ~^(?!craue_config_setting)~
```

## Usage

Create settings in the database (preferably using a migration):

```sql
insert into
    itkdev_setting(section, name, type, form_type, value_string)
values
    ('cms', 'about_header', 'string', 'text', 'About this application');

insert into
    itkdev_setting(section, name, type, form_type, value_text)
values
    ('cms', 'about, 'text', 'text', 'This application handles configuration on the database.);
```

### Twig

See <https://github.com/craue/CraueConfigBundle/#usage-in-twig-templates>.

## Development

``` shell
docker run --rm --volume ${PWD}:/app itkdev/php8.2-fpm:latest composer install
```

``` shell
docker run --rm --volume ${PWD}:/app itkdev/php8.2-fpm:latest composer coding-standards-check

```
