<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Kmergen\DoctrineTranslatable\Contract\Entity\TranslatableInterface;
use Kmergen\DoctrineTranslatable\Contract\Entity\TranslationInterface;

/**
 * Adds the translatable <-> translations association mapping at runtime.
 */
#[AsDoctrineListener(event: Events::loadClassMetadata)]
final readonly class LoadClassMetadataListener
{
    public const string LOCALE = 'locale';

    private int $translatableFetchMode;

    private int $translationFetchMode;

    public function __construct(
        string $translatableFetchMode = 'LAZY',
        string $translationFetchMode = 'LAZY',
    ) {
        $this->translatableFetchMode = $this->convertFetchMode($translatableFetchMode);
        $this->translationFetchMode = $this->convertFetchMode($translationFetchMode);
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
    {
        $classMetadata = $args->getClassMetadata();
        if ($classMetadata->isMappedSuperclass) {
            return;
        }

        $className = $classMetadata->getName();

        if (is_a($className, TranslatableInterface::class, true)) {
            $this->mapTranslatable($classMetadata);
        }

        if (is_a($className, TranslationInterface::class, true)) {
            $this->mapTranslation($classMetadata, $args->getObjectManager());
        }
    }

    private function convertFetchMode(string $fetchMode): int
    {
        return match (strtoupper($fetchMode)) {
            'EAGER' => ClassMetadata::FETCH_EAGER,
            'EXTRA_LAZY' => ClassMetadata::FETCH_EXTRA_LAZY,
            default => ClassMetadata::FETCH_LAZY,
        };
    }

    /**
     * @param ClassMetadata<object> $classMetadata
     */
    private function mapTranslatable(ClassMetadata $classMetadata): void
    {
        if ($classMetadata->hasAssociation('translations')) {
            return;
        }

        /** @var class-string<TranslatableInterface> $className */
        $className = $classMetadata->getName();

        $classMetadata->mapOneToMany([
            'fieldName' => 'translations',
            'mappedBy' => 'translatable',
            'indexBy' => self::LOCALE,
            'cascade' => ['persist', 'remove'],
            'fetch' => $this->translatableFetchMode,
            'targetEntity' => $className::getTranslationEntityClass(),
            'orphanRemoval' => true,
        ]);
    }

    /**
     * @param ClassMetadata<object> $classMetadata
     */
    private function mapTranslation(ClassMetadata $classMetadata, ObjectManager $objectManager): void
    {
        if (!$classMetadata->hasAssociation('translatable')) {
            /** @var class-string<TranslationInterface> $className */
            $className = $classMetadata->getName();
            $targetEntity = $className::getTranslatableEntityClass();

            /** @var ClassMetadata<object> $targetMetadata */
            $targetMetadata = $objectManager->getClassMetadata($targetEntity);

            $classMetadata->mapManyToOne([
                'fieldName' => 'translatable',
                'inversedBy' => 'translations',
                'cascade' => ['persist'],
                'fetch' => $this->translationFetchMode,
                'joinColumns' => [
                    [
                        'name' => 'translatable_id',
                        'referencedColumnName' => $targetMetadata->getSingleIdentifierColumnName(),
                        'onDelete' => 'CASCADE',
                    ],
                ],
                'targetEntity' => $targetEntity,
            ]);
        }

        $name = $classMetadata->getTableName() . '_unique_translation';
        if (
            !isset($classMetadata->table['uniqueConstraints'][$name])
            && $classMetadata->getName() === $classMetadata->rootEntityName
        ) {
            $classMetadata->table['uniqueConstraints'][$name] = [
                'columns' => ['translatable_id', self::LOCALE],
            ];
        }

        if (!$classMetadata->hasField(self::LOCALE) && !$classMetadata->hasAssociation(self::LOCALE)) {
            $classMetadata->mapField([
                'fieldName' => self::LOCALE,
                'type' => 'string',
                'length' => 5,
            ]);
        }
    }
}
