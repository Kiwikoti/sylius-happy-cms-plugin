# Sylius Happy CMS Plugin

Enhance your Sylius with CMS features with this plugin. 

Keep in mind : Happy People make Happy Internet.

## Installation

Actually we don't have Symfony flex configured, so you have to do some installation step manually :

1. Add into `config/packages/sylius_resource.yaml` :

```
imports:
    - { resource: "@SyliusHappyCMSPlugin/Resources/config/sylius_resource.yaml" }
```


2. Add into `config/packages/doctrine.yaml` :

```
imports:
    - { resource: "@SyliusHappyCMSPlugin/Resources/config/doctrine.yaml" }
```

Then, into `config/bundles.php` add :

```php
Adeliom\SyliusHappyCMSPlugin\SyliusHappyCMSPlugin::class => ['all' => true],
Adeliom\SyliusEasyCrudPlugin\SyliusEasyCrudPlugin::class => ['all' => true],
```

Then, into `config/packages/_sylius.yaml` add :

```yaml
imports:
  - { resource: "@SyliusHappyCMSPlugin/config/config.yaml" }
```

Then, into `config/routes.yaml` add :

```yaml
sylius_easy_crud:
  resource: "@SyliusHappyCMSPlugin/config/routes.yaml"
```

3. Generate default file in your project (entities, repositories and admin classes) :

Actualy we don't have Symfony recipes, so we created a command to generate files automatically.

```bash
php bin/console make:happy-cms:install
```

This command also provide all variables you need to override. Don't forget to do that!

4. Update database :

```bash
php bin/console doc:mig:diff
php bin/console doc:mig:mig
```


## Documentation

- TODO
- [Discover all fields](./docs/discover_fields.md) you can use to build your CRUD (grid, form, show, action, fitters)
- Learn how create your [own fields](./docs/create_your_own_fields.md)
- You want to [help and contribute](./docs/contribution.md)

## License

[MIT](https://choosealicense.com/licenses/mit/)

## Authors

Adeliom
