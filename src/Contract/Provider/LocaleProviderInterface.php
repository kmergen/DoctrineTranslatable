<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Contract\Provider;

interface LocaleProviderInterface
{
    public function provideCurrentLocale(): ?string;

    public function provideFallbackLocale(): ?string;
}
