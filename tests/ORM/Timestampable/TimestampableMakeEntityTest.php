<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\ORM\Timestampable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Kmergen\DoctrineTranslatable\Tests\AbstractBehaviorTestCase;
use Kmergen\DoctrineTranslatable\Tests\ORM\Timestampable\Source\SomeClassForMetadata;

/**
 * When console make:entity creates a new class, the event arguments are not fully populated
 *
 * This test emulates the event dispatch near the end of method
 * Doctrine\ORM\Mapping\ClassMetadataFactory::doLoadMetadata()
 */
final class TimestampableMakeEntityTest extends AbstractBehaviorTestCase
{
    public function testMakeEntityEmptyEvent(): void
    {
        $classMetadata = new ClassMetadata(SomeClassForMetadata::class);
        $loadClassMetadataEventArgs = new LoadClassMetadataEventArgs($classMetadata, $this->entityManager);

        $doctrineEventManager = $this->entityManager->getEventManager();
        $doctrineEventManager->dispatchEvent(Events::loadClassMetadata, $loadClassMetadataEventArgs);
        $this->expectNotToPerformAssertions();
    }
}