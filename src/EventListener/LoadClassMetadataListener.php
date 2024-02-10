<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\EventListener;

use Kmergen\DoctrineTranslatable\Contract\Entity\TranslatableInterface;
use Kmergen\DoctrineTranslatable\Contract\Entity\TranslationInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\Events;
use ReflectionClass;

#[AsDoctrineListener(event: Events::loadClassMetadata)]
final class LoadClassMetadataListener extends BaseTranslatableListener
{
    /**
     * @var string
     */
    public const LOCALE = 'locale';

    /**
     * Adds mapping to the translatable and translations.
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
    {
        $classMetadata = $args->getClassMetadata();
        $reflClass = $classMetadata->getReflectionClass();
        if (!$reflClass instanceof ReflectionClass) {
            // Class has not yet been fully built, ignore this event
            return;
        }

        if ($classMetadata->isMappedSuperclass) {
            return;
        }

        if (is_a($reflClass->getName(), TranslatableInterface::class, true)) {
            $this->mapTranslatable($classMetadata);
        }

        if (is_a($reflClass->getName(), TranslationInterface::class, true)) {
            $this->mapTranslation($classMetadata, $args->getObjectManager());
        }

    }

    private function mapTranslatable(ClassMetadata $classMetadata): void
    {
        if ($classMetadata->hasAssociation('translations')) {
            return;
        }

        $classMetadata->mapOneToMany([
            'fieldName' => 'translations',
            'mappedBy' => 'translatable',
            'indexBy' => self::LOCALE,
            'cascade' => ['persist', 'remove'],
            'fetch' => $this->translatableFetchMode,
            'targetEntity' => $classMetadata->getReflectionClass()
                ->getMethod('getTranslationEntityClass')
                ->invoke(null),
            'orphanRemoval' => true,
        ]);
    }

    private function mapTranslation(ClassMetadata $classMetadata, ObjectManager $objectManager): void
    {
        if (!$classMetadata->hasAssociation('translatable')) {
            $targetEntity = $classMetadata->getReflectionClass()
                ->getMethod('getTranslatableEntityClass')
                ->invoke(null);

            /** @var ClassMetadata $classMetadata */
            $classMetadataTarget = $objectManager->getClassMetadata($targetEntity);
            $singleIdentifierFieldName = $classMetadataTarget->getSingleIdentifierFieldName();

            $classMetadata->mapManyToOne([
                'fieldName' => 'translatable',
                'inversedBy' => 'translations',
                'cascade' => ['persist'],
                'fetch' => $this->translationFetchMode,
                'joinColumns' => [
                    [
                        'name' => 'translatable_id',
                        'referencedColumnName' => $singleIdentifierFieldName,
                        'onDelete' => 'CASCADE',
                    ]
                ],
                'targetEntity' => $targetEntity,
            ]);
        }

        $name = $classMetadata->getTableName() . '_unique_translation';
        if (
            !$this->hasUniqueTranslationConstraint($classMetadata, $name) &&
            $classMetadata->getName() === $classMetadata->rootEntityName
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

    private function hasUniqueTranslationConstraint(ClassMetadata $classMetadata, string $name): bool
    {
        return isset($classMetadata->table['uniqueConstraints'][$name]);
    }

}
