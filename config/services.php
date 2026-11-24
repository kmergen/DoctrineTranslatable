<?php

declare(strict_types=1);

use Kmergen\DoctrineTranslatable\Contract\Provider\LocaleProviderInterface;
use Kmergen\DoctrineTranslatable\EventListener\LoadClassMetadataListener;
use Kmergen\DoctrineTranslatable\EventListener\TranslatableLocaleListener;
use Kmergen\DoctrineTranslatable\Provider\LocaleProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // Allowed values: LAZY, EAGER, EXTRA_LAZY
    $parameters->set('doctrine_behaviors_translatable_fetch_mode', 'LAZY');
    $parameters->set('doctrine_behaviors_translation_fetch_mode', 'LAZY');

    $services = $containerConfigurator->services();

    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->set(LocaleProvider::class);
    $services->alias(LocaleProviderInterface::class, LocaleProvider::class);

    $services->set(LoadClassMetadataListener::class)
        ->arg('$translatableFetchMode', '%doctrine_behaviors_translatable_fetch_mode%')
        ->arg('$translationFetchMode', '%doctrine_behaviors_translation_fetch_mode%');

    $services->set(TranslatableLocaleListener::class);
};
