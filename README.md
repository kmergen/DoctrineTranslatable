# Doctrine Translatable for Symfony

This symfony bundle provides a behavior to translate Doctrine entities in Symfony.
This bundle is compatible with Symfony versions equal or greater Symfony 7.0.

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

## Credits

This bundle is highly inspired by the excellent work of [Knplaps](https://github.com/KnpLabs) on its bundle: 

- [DoctrineBehaviors](https://github.com/KnpLabs/DoctrineBehaviors)

In fact, we have used some of its processes, commands, views, and some of its attribute names to somehow standardize the 
bundle and make it easier for us to build our
own bundle for Symfony.
