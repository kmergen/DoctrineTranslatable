<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

final readonly class DatabaseLoader
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function reload(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $this->entityManager->getConfiguration()->setUseSchemaEditorApi(true);

        $schemaTool = new SchemaTool($this->entityManager);
        // every test boots a fresh kernel with a fresh in-memory SQLite database
        $schemaTool->createSchema($metadata);
    }
}
