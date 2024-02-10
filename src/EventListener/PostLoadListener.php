<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventListener;


use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::postLoad)]
final class PostLoadListener extends BaseTranslatableListener
{
    public function postLoad(PostLoadEventArgs $args): void
    {
        $this->setLocales($args);
    }
}
