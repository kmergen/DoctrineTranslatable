<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\Translatable;

use Doctrine\ORM\Mapping\Entity;

#[Entity]
class ExtendedTranslatableEntityWithJoinTableInheritanceTranslation extends TranslatableEntityWithJoinTableInheritanceTranslation
{
}
