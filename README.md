# Settings

## Installation

```sh
composer require itk-dev/settings-bundle "^3.0"
```

Enable the bundle in `app/AppKernel.php`:

```php
public function registerBundles() {
    $bundles = [
        // …
        // Start of required dependencies of ItkDevConfigBundle
        new Craue\ConfigBundle\CraueConfigBundle(),
        new EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle(),
        // End of required dependencies for ItkDevConfigBundle
        new ItkDev\ConfigBundle\ItkDevConfigBundle(),
    ];
    // …
}
```

In `app/config/config.yml`:

```yml
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

```yml
doctrine:
    orm:
        entity_managers:
            default:
                mappings:
                    …
                    ItkDevConfigBundle: ~
```


If using [Doctrine
migrations](https://github.com/doctrine/DoctrineMigrationsBundle), you
should [filter out the `craue_config_setting` table from
migrations](https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html#manual-tables):

```yml
doctrine:
    dbal:
        [...]
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

Easy admin:

See [Resources/config/easy_admin.yml](Resources/config/easy_admin.yml) for an example Easy Admin configuration for Settings.

Twig:

See https://github.com/craue/CraueConfigBundle/#usage-in-twig-templates



## Rich text

To use the form type `fos_ckeditor`, you have to enable
[FOSCKEditorBundle](https://symfony.com/doc/master/bundles/FOSCKEditorBundle/index.html)
(which is already installed).

Update your `composer.json` using the guidelines at
https://symfony.com/doc/master/bundles/FOSCKEditorBundle/usage/ckeditor.html#composer-script,
e.g. add

```json
    "ckeditor:install --release=basic --tag=4.6.0 --clear=drop --exclude=samples": "symfony-cmd",
```
