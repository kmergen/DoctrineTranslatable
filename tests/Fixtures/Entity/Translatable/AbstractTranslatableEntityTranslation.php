<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\Translatable;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Kmergen\DoctrineTranslatable\Contract\Entity\TranslationInterface;
use Kmergen\DoctrineTranslatable\Exception\ShouldNotHappenException;
use Kmergen\DoctrineTranslatable\Model\Translatable\TranslationTrait;

#[MappedSuperclass]
abstract class AbstractTranslatableEntityTranslation implements TranslationInterface
{
    use TranslationTrait;

    #[Column(type: 'string')]
    private ?string $title = null;

    public function getTitle(): string
    {
        if ($this->title === null) {
            throw new ShouldNotHappenException();
        }

        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
