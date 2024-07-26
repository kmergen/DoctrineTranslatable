<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable;

use Kmergen\DoctrineTranslatable\Bundle\DependencyInjection\DoctrineTranslatableExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineTranslatableBundle extends Bundle
{
  public function getContainerExtension(): Extension
  {
    return new DoctrineTranslatableExtension();
  }
}
