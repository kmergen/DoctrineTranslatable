<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Model\Translatable;

use Kmergen\DoctrineTranslatable\Contract\Entity\TranslatableInterface;

trait TranslationPropertiesTrait
{
    /**
     * Mapped at runtime by LoadClassMetadataListener.
     */
    protected ?string $locale = null;

    /**
     * Mapped at runtime by LoadClassMetadataListener.
     */
    protected ?TranslatableInterface $translatable = null;
}
