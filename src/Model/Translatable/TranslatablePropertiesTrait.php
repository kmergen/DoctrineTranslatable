<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Model\Translatable;

use Doctrine\Common\Collections\Collection;
use Kmergen\DoctrineTranslatable\Contract\Entity\TranslationInterface;

trait TranslatablePropertiesTrait
{
    /**
     * Mapped at runtime by LoadClassMetadataListener (indexed by locale).
     *
     * @var Collection<string, TranslationInterface>|null
     */
    protected ?Collection $translations = null;

    /**
     * Translations created via translate() that are not yet merged.
     *
     * @see mergeNewTranslations()
     *
     * @var Collection<string, TranslationInterface>|null
     */
    protected ?Collection $newTranslations = null;

    /**
     * Not persisted; set by TranslatableLocaleListener on postLoad/prePersist.
     */
    protected ?string $currentLocale = null;

    /**
     * Not persisted; overwritten by TranslatableLocaleListener with the fallback locale.
     */
    protected string $defaultLocale = 'de';
}
