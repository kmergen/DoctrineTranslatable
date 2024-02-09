<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostUpdateEventArgs;


class TestEventSubscriber implements EventSubscriber
{

    private ?string $hu = null;
    public function __construct()
    {
        $this->hu = 4;
    }


    public function getSubscribedEvents()
    {
        return array(Events::preUpdate);
    }

    public function postUpdate(PostUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();
    }
}
