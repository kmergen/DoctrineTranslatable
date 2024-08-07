<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class DoctrineTranslatableExtension extends Extension
{
  /**
   * @param array $configs
   */
  public function load(array $configs, ContainerBuilder $containerBuilder): void
  {
    $phpFileLoader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../../config'));
    $phpFileLoader->load('services.php');
  }
}
