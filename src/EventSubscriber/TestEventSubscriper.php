<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;


class TestEventSubscriber implements EventSubscriber
{
    public $preFooInvoked = false;

    public function preFoo()
    {
        $this->preFooInvoked = true;
    }

    public function getSubscribedEvents()
    {
        return array(Events::prePersist);
    }
}
