# Settings

## Installation

```sh
composer require itk-dev/settings-bundle "^1.0"
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

```yml
imports:
    - { resource: '@ItkDevConfigBundle/Resources/config/easy_admin.yml' }
```

Twig:

See https://github.com/craue/CraueConfigBundle/#usage-in-twig-templates



## Rich text

To use the form type `ckeditor`, you have to enable
[IvoryCKEditorBundle](http://symfony.com/doc/master/bundles/IvoryCKEditorBundle/index.html)
(which is already installed).

Follow steps 2–4 on
https://symfony.com/doc/master/bundles/EasyAdminBundle/integration/ivoryckeditorbundle.html#installing-the-rich-text-editor
to enable the bundle.
