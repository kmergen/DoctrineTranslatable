<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Model\Translatable;

use Doctrine\Common\Collections\Collection;
use Kmergen\DoctrineTranslatable\Contract\Entity\TranslationInterface;

trait TranslatablePropertiesTrait
{
  /**
   * @var Collection<string, TranslationInterface>
   */
  protected $translations;

  /**
   * @see mergeNewTranslations
   * @var Collection<string, TranslationInterface>
   */
  protected $newTranslations;

  /**
   * currentLocale is a non persisted field configured during postLoad event
   *
   * @var string|null
   */
  protected $currentLocale;

  /**
   * @var string
   */
  protected $defaultLocale = 'de';
}
