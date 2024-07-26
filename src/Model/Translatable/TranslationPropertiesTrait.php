<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Model\Translatable;

use Kmergen\DoctrineTranslatable\Contract\Entity\TranslatableInterface;

trait TranslationPropertiesTrait
{
  /**
   * @var string
   */
  protected $locale;

  /**
   * Will be mapped to translatable entity by TranslatableSubscriber
   *
   * @var TranslatableInterface
   */
  protected $translatable;
}
