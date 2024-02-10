<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventListener;


use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist)]
final class PrePersistListener extends BaseTranslatableListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->setLocales($args);
    }
}
