<?php

declare(strict_types=1);

use Kmergen\DoctrineTranslatable\Contract\Provider\LocaleProviderInterface;
use Kmergen\DoctrineTranslatable\Tests\DatabaseLoader;
use Kmergen\DoctrineTranslatable\Tests\Provider\TestLocaleProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('locale', 'en');

    $containerConfigurator->extension('framework', [
        'secret' => 'test',
        'test' => true,
        'http_method_override' => false,
        'handle_all_throwables' => true,
        'php_errors' => ['log' => true],
    ]);

    $containerConfigurator->extension('doctrine', [
        'dbal' => [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ],
        'orm' => [
            'mappings' => [
                'TranslatableFixtures' => [
                    'type' => 'attribute',
                    'prefix' => 'Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\\',
                    'dir' => __DIR__ . '/../Fixtures/Entity',
                    'is_bundle' => false,
                ],
            ],
        ],
    ]);

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->set(TestLocaleProvider::class);
    $services->alias(LocaleProviderInterface::class, TestLocaleProvider::class);

    $services->set(DatabaseLoader::class);
};
