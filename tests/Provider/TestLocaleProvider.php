<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\Provider;

use Kmergen\DoctrineTranslatable\Contract\Provider\LocaleProviderInterface;

final class TestLocaleProvider implements LocaleProviderInterface
{
    public function provideCurrentLocale(): ?string
    {
        return 'en';
    }

    public function provideFallbackLocale(): ?string
    {
        return 'en';
    }
}
