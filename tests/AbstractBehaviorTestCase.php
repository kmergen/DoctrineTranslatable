<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Kmergen\DoctrineTranslatable\Tests\HttpKernel\TestKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractBehaviorTestCase extends TestCase
{
    protected EntityManagerInterface $entityManager;

    private TestKernel $kernel;

    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->kernel = new TestKernel($this->provideCustomConfigs());
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();

        $this->entityManager = $this->getService('doctrine.orm.entity_manager');
        $this->getService(DatabaseLoader::class)->reload();
    }

    protected function tearDown(): void
    {
        $this->kernel->shutdown();
    }

    /**
     * @return list<string>
     */
    protected function provideCustomConfigs(): array
    {
        return [];
    }

    /**
     * @template T of object
     *
     * @param class-string<T>|string $id
     *
     * @return ($id is class-string<T> ? T : object)
     */
    protected function getService(string $id): object
    {
        return $this->container->get($id);
    }
}
