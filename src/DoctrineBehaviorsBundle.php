<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable;

use Kmergen\DoctrineTranslatable\Bundle\DependencyInjection\DoctrineBehaviorsExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineBehaviorsBundle extends Bundle
{
    public function getContainerExtension(): Extension
    {
        return new DoctrineBehaviorsExtension();
    }
}