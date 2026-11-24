# Doctrine Translatable for Symfony

This symfony bundle provides a behavior to translate Doctrine entities in Symfony.
Requires PHP >= 8.4, Symfony >= 8.1 and Doctrine ORM 3.

For other Symfony versions you should consider to use the [DoctrineBehaviors](https://github.com/KnpLabs/DoctrineBehaviors) bundle.

It currently handles:

-   [Translatable](/docs/translatable.md)

## Install

```bash
composer require kmergen/doctrine-translatable
```

## Usage

All you have to do is to define a Doctrine entity:

-   implemented interface
-   add a trait

For further informations how to use it, you can also look the documentation of [DoctrineBehaviors](https://github.com/KnpLabs/DoctrineBehaviors) bundle.

## Locale handling

`TranslatableLocaleListener` sets the *current* locale (request locale) and the *default/fallback*
locale (request default locale, `%locale%` or `%kernel.default_locale%`) on every translatable
entity on `postLoad` and `prePersist`. So `$entity->translate()` without argument returns the
translation of the current request locale and falls back to the default locale.

Before an entity is persisted the trait default `de` is used.
To use your own logic, alias `Kmergen\DoctrineTranslatable\Contract\Provider\LocaleProviderInterface`
to your own service.

## Fetch modes

```yaml
# config/services.yaml
parameters:
    doctrine_behaviors_translatable_fetch_mode: LAZY  # LAZY | EAGER | EXTRA_LAZY
    doctrine_behaviors_translation_fetch_mode: LAZY
```

## PHPStan

Optional extension for precise return types of `translate()`, `getTranslations()` and `getTranslatable()`:

```neon
includes:
    - vendor/kmergen/doctrine-translatable/extension.neon
```

## Development

```bash
composer install
composer test      # PHPUnit (SQLite in-memory)
composer phpstan   # PHPStan level 6
vendor/bin/rector process --dry-run
```

## Credits

This bundle is highly inspired by the excellent work of [Knplaps](https://github.com/KnpLabs) on its bundle: 

- [DoctrineBehaviors](https://github.com/KnpLabs/DoctrineBehaviors)

In fact, we have used some of its processes, commands, views, and some of its attribute names to somehow standardize the 
bundle and make it easier for us to build our
own bundle for Symfony.
