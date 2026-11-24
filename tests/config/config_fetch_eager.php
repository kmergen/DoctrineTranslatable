<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('doctrine_behaviors_translatable_fetch_mode', 'EAGER')
        ->set('doctrine_behaviors_translation_fetch_mode', 'EXTRA_LAZY');
};
