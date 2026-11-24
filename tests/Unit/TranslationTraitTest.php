<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\Unit;

use Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\TranslatableEntity;
use Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\TranslatableEntityTranslation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TranslationTraitTest extends TestCase
{
    public function testDefaultTranslatableEntityClassStripsTranslationSuffix(): void
    {
        $this->assertSame(TranslatableEntity::class, TranslatableEntityTranslation::getTranslatableEntityClass());
        $this->assertSame(TranslatableEntityTranslation::class, TranslatableEntity::getTranslationEntityClass());
    }

    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function provideTitles(): iterable
    {
        yield 'text' => ['abc', false];
        yield 'zero' => ['0', false];
        yield 'empty' => ['', true];
        yield 'whitespace only' => ["  \t\n", true];
    }

    #[DataProvider('provideTitles')]
    public function testIsEmpty(string $title, bool $expectedEmpty): void
    {
        $translation = new TranslatableEntityTranslation();
        $translation->setLocale('en');
        $translation->setTitle($title);

        $this->assertSame($expectedEmpty, $translation->isEmpty());
    }

    public function testNewTranslationWithoutValuesIsEmpty(): void
    {
        $this->assertTrue(new TranslatableEntity()->translate('fr')->isEmpty());
    }

    public function testCountryLocaleFallsBackToLanguage(): void
    {
        $entity = new TranslatableEntity();
        $entity->translate('fr', false)->setTitle('sac plastique');
        $entity->mergeNewTranslations();

        $this->assertSame('sac plastique', $entity->translate('fr_CH')->getTitle());
    }

    public function testMagicProxyUsesCurrentLocale(): void
    {
        $entity = new TranslatableEntity();
        $entity->setCurrentLocale('nl');
        // __call -> proxyCurrentLocaleTranslation
        $entity->setTitle('hallo');

        $this->assertSame('hallo', $entity->translate('nl')->getTitle());
        $this->assertSame('hallo', $entity->getTitle());
    }
}
