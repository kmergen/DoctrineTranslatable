<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\Bundle;

use Doctrine\ORM\Mapping\ClassMetadata;
use Kmergen\DoctrineTranslatable\DoctrineTranslatableBundle;
use Kmergen\DoctrineTranslatable\EventListener\LoadClassMetadataListener;
use Kmergen\DoctrineTranslatable\EventListener\TranslatableLocaleListener;
use Kmergen\DoctrineTranslatable\Tests\AbstractBehaviorTestCase;
use Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\TranslatableEntity;
use Kmergen\DoctrineTranslatable\Tests\Fixtures\Entity\TranslatableEntityTranslation;
use Kmergen\DoctrineTranslatable\Tests\HttpKernel\TestKernel;
use PHPUnit\Framework\TestCase;

final class BundleIntegrationTest extends TestCase
{
    public function testBundleRegistersOnlyListenersAndProviderAsPrivateServices(): void
    {
        $kernel = new TestKernel();
        $kernel->boot();

        // test.service_container exposes private services
        $container = $kernel->getContainer()->get('test.service_container');

        $this->assertInstanceOf(LoadClassMetadataListener::class, $container->get(LoadClassMetadataListener::class));
        $this->assertInstanceOf(TranslatableLocaleListener::class, $container->get(TranslatableLocaleListener::class));

        // private => not reachable from the real container
        $this->assertFalse($kernel->getContainer()->has(LoadClassMetadataListener::class));
        $this->assertFalse($kernel->getContainer()->has(TranslatableLocaleListener::class));

        // the bundle class itself must not be registered as service
        $this->assertFalse($container->has(DoctrineTranslatableBundle::class));

        $kernel->shutdown();
    }

    public function testDefaultFetchModeIsLazy(): void
    {
        $kernel = new TestKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->assertSame(
            ClassMetadata::FETCH_LAZY,
            $em->getClassMetadata(TranslatableEntity::class)->getAssociationMapping('translations')->fetch
        );

        $kernel->shutdown();
    }

    public function testFetchModeCanBeConfigured(): void
    {
        $kernel = new TestKernel([__DIR__ . '/../config/config_fetch_eager.php']);
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->assertSame(
            ClassMetadata::FETCH_EAGER,
            $em->getClassMetadata(TranslatableEntity::class)->getAssociationMapping('translations')->fetch
        );
        $this->assertSame(
            ClassMetadata::FETCH_EXTRA_LAZY,
            $em->getClassMetadata(TranslatableEntityTranslation::class)->getAssociationMapping('translatable')->fetch
        );

        $kernel->shutdown();
    }
}
