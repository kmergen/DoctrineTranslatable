<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\Blameable;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Kmergen\DoctrineTranslatable\Contract\Entity\BlameableInterface;
use Kmergen\DoctrineTranslatable\Exception\ShouldNotHappenException;
use Kmergen\DoctrineTranslatable\Model\Blameable\BlameableTrait;

#[Entity]
class BlameableEntity implements BlameableInterface
{
    use BlameableTrait;

    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: true)]
    private ?string $title = null;

    public function getId(): int
    {
        return $this->id;
    }

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
