<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventListener;

use Kmergen\DoctrineTranslatable\Contract\Provider\LocaleProviderInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata as ClassMetadataInfo;

class BaseTranslatableListener
{
  protected int $translatableFetchMode;

  protected int $translationFetchMode;

  public function __construct(
    private readonly LocaleProviderInterface $localeProvider,
    string                                   $translatableFetchMode,
    string                                   $translationFetchMode
  ) {
    $this->translatableFetchMode = $this->convertFetchString($translatableFetchMode);
    $this->translationFetchMode = $this->convertFetchString($translationFetchMode);
  }

  private function convertFetchString(string|int $fetchMode): int
  {
    if (is_int($fetchMode)) {
      return $fetchMode;
    }

    if ($fetchMode === 'EAGER') {
      return ClassMetadataInfo::FETCH_EAGER;
    }

    if ($fetchMode === 'EXTRA_LAZY') {
      return ClassMetadataInfo::FETCH_EXTRA_LAZY;
    }

    return ClassMetadataInfo::FETCH_LAZY;
  }

  protected function setLocales(PostLoadEventArgs|PrePersistEventArgs $lifecycleEventArgs): void
  {
    $entity = $lifecycleEventArgs->getObject();
    if (!$entity instanceof TranslatableInterface) {
      return;
    }

    $currentLocale = $this->localeProvider->provideCurrentLocale();
    if ($currentLocale) {
      $entity->setCurrentLocale($currentLocale);
    }

    $fallbackLocale = $this->localeProvider->provideFallbackLocale();
    if ($fallbackLocale) {
      $entity->setDefaultLocale($fallbackLocale);
    }
  }

}
