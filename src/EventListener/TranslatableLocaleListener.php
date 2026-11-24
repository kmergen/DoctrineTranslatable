<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Kmergen\DoctrineTranslatable\Contract\Entity\TranslatableInterface;
use Kmergen\DoctrineTranslatable\Contract\Provider\LocaleProviderInterface;

/**
 * Sets the current and default (fallback) locale on translatable entities
 * when they are loaded from or persisted to the database.
 */
#[AsDoctrineListener(event: Events::postLoad)]
#[AsDoctrineListener(event: Events::prePersist)]
final readonly class TranslatableLocaleListener
{
    public function __construct(
        private LocaleProviderInterface $localeProvider,
    ) {
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $this->setLocales($args->getObject());
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->setLocales($args->getObject());
    }

    private function setLocales(object $entity): void
    {
        if (!$entity instanceof TranslatableInterface) {
            return;
        }

        $currentLocale = $this->localeProvider->provideCurrentLocale();
        if ($currentLocale !== null && $currentLocale !== '') {
            $entity->setCurrentLocale($currentLocale);
        }

        $fallbackLocale = $this->localeProvider->provideFallbackLocale();
        if ($fallbackLocale !== null && $fallbackLocale !== '') {
            $entity->setDefaultLocale($fallbackLocale);
        }
    }
}
