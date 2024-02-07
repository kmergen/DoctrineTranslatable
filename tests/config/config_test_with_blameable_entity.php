<?php

declare(strict_types=1);

use Kmergen\DoctrineTranslatable\Contract\Provider\UserProviderInterface;
use Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\UserEntity;
use Kmergen\DoctrineTranslatable\Tests\Provider\EntityUserProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('doctrine_behaviors_blameable_user_entity', UserEntity::class);

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->set(EntityUserProvider::class);
    $services->alias(UserProviderInterface::class, EntityUserProvider::class);
};
