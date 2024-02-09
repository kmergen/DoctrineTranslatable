<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventListener;


use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::postUpdate, priority: 500, connection: 'default')]
class Tester1
{
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();


        $entityManager = $args->getObjectManager();
        // ... do somethi

    }
}
