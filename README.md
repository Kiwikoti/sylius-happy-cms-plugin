# Sylius Happy CMS Plugin

Enhance your Sylius with CMS features with this plugin. 

Keep in mind : Happy People make Happy Internet.

## Installation

1. Add into `config/packages/sylius_resource.yaml' :

```
imports:
    - { resource: "@SyliusHappyCMSPlugin/Resources/config/sylius_resource.yaml" }
```


2. Add into `config/packages/doctrine.yaml' :

```
imports:
    - { resource: "@SyliusHappyCMSPlugin/Resources/config/doctrine.yaml" }
```

Then, into `config/bundles.php` add :

```php
`Adeliom\SyliusHappyCMSPlugin\SyliusHappyCMSPlugin::class => ['all' => true],
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

## Documentation

- TODO
- [Discover all fields](./docs/discover_fields.md) you can use to build your CRUD (grid, form, show, action, fitters)
- Learn how create your [own fields](./docs/create_your_own_fields.md)
- You want to [help and contribute](./docs/contribution.md)

## License

[MIT](https://choosealicense.com/licenses/mit/)

## Authors

Adeliom
