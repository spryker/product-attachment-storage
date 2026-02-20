<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Business\Writer;

use Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer;
use Generated\Shared\Transfer\ProductAttachmentStorageTransfer;
use Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage;
use Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageEntityManagerInterface;
use Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageRepositoryInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

class ProductAbstractAttachmentStorageWriter implements ProductAbstractAttachmentStorageWriterInterface
{
    use ActiveRecordBatchProcessorTrait;

    protected const string DEFAULT_LOCALE = 'default';

    public function __construct(
        protected ProductAttachmentStorageRepositoryInterface $repository,
        protected ProductAttachmentStorageEntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param array<int> $productAbstractIds
     */
    public function publish(array $productAbstractIds): void
    {
        $productAbstractIds = array_unique($productAbstractIds);

        $productAttachmentProductAbstractEntitiesByIdProductAbstractAndLocale = $this
            ->getProductAttachmentProductAbstractEntitiesByIdProductAbstractAndLocale($productAbstractIds);

        $existingStorageEntitiesByProductAbstractIdAndLocale = $this->repository
            ->getProductAbstractAttachmentStorageEntitiesByProductAbstractIds($productAbstractIds);

        $this->storeData(
            $productAttachmentProductAbstractEntitiesByIdProductAbstractAndLocale,
            $existingStorageEntitiesByProductAbstractIdAndLocale,
        );
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, array<string, array<\Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstract>>>
     */
    protected function getProductAttachmentProductAbstractEntitiesByIdProductAbstractAndLocale(array $productAbstractIds): array
    {
        $groupedProductAttachmentProductAbstractEntities = [];

        $productAttachmentProductAbstractEntities = $this->repository
            ->getProductAttachmentsProductAbstractEntitiesByProductAbstractIds($productAbstractIds);

        foreach ($productAttachmentProductAbstractEntities as $productAttachmentProductAbstractEntity) {
            $idProductAbstract = $productAttachmentProductAbstractEntity->getFkProductAbstract();
            $localeName = $productAttachmentProductAbstractEntity
                ->getProductAttachment()
                ->getLocale()
                ?->getLocaleName() ?? static::DEFAULT_LOCALE;
            $groupedProductAttachmentProductAbstractEntities[$idProductAbstract][$localeName][] = $productAttachmentProductAbstractEntity;
        }

        return $groupedProductAttachmentProductAbstractEntities;
    }

    /**
     * @param array<int, array<string, array<\Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstract>>> $productAttachmentProductAbstractByProductAbstractIdAndLocale
     * @param array<int, array<string, \Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage>> $existingStorageEntitiesByProductAbstractIdAndLocale
     */
    protected function storeData(
        array $productAttachmentProductAbstractByProductAbstractIdAndLocale,
        array $existingStorageEntitiesByProductAbstractIdAndLocale,
    ): void {
        $storedKeys = [];

        foreach ($productAttachmentProductAbstractByProductAbstractIdAndLocale as $idProductAbstract => $productAttachmentProductAbstractEntitiesByLocale) {
            foreach ($productAttachmentProductAbstractEntitiesByLocale as $localeName => $productAttachmentProductAbstractEntities) {
                $this->storeDataForLocale(
                    $idProductAbstract,
                    $localeName,
                    $productAttachmentProductAbstractEntities,
                    $existingStorageEntitiesByProductAbstractIdAndLocale[$idProductAbstract][$localeName] ?? null,
                );

                $storedKeys[$idProductAbstract][$localeName] = true;
            }
        }

        $this->deleteObsoleteStorageEntries(
            $existingStorageEntitiesByProductAbstractIdAndLocale,
            $storedKeys,
        );

        $this->commit();
    }

    /**
     * @param int $productAbstractId
     * @param array<\Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstract> $productAttachmentProductAbstractEntities
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer
     */
    protected function mapProductAttachmentTransfersToStorageTransfers(
        int $productAbstractId,
        array $productAttachmentProductAbstractEntities,
    ): ProductAbstractAttachmentStorageTransfer {
        $productAbstractAttachmentStorageTransfer = new ProductAbstractAttachmentStorageTransfer();
        $productAbstractAttachmentStorageTransfer->setIdProductAbstract($productAbstractId);

        foreach ($productAttachmentProductAbstractEntities as $productAttachmentProductAbstractEntity) {
            $productAbstractAttachmentStorageTransfer->addAttachment(
                (new ProductAttachmentStorageTransfer())
                    ->setSortOrder($productAttachmentProductAbstractEntity->getOrder())
                    ->fromArray($productAttachmentProductAbstractEntity->getProductAttachment()->toArray(), true),
            );
        }

        return $productAbstractAttachmentStorageTransfer;
    }

    /**
     * @param array<\Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstract> $productAttachmentProductAbstractEntities
     */
    protected function storeDataForLocale(
        int $idProductAbstract,
        string $localeName,
        array $productAttachmentProductAbstractEntities,
        ?SpyProductAbstractAttachmentStorage $storageEntity,
    ): void {
        $productAbstractAttachmentStorageTransfer = $this->mapProductAttachmentTransfersToStorageTransfers(
            $idProductAbstract,
            $productAttachmentProductAbstractEntities,
        );

        $storageEntity = $storageEntity ?? new SpyProductAbstractAttachmentStorage();
        $storageEntity->setFkProductAbstract($idProductAbstract);
        $storageEntity->setLocale($localeName);
        $storageEntity->setData($productAbstractAttachmentStorageTransfer->toArray());
        $this->persist($storageEntity);
    }

    /**
     * @param array<int, array<string, \Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage>> $existingStorageEntitiesByProductAbstractIdAndLocale
     * @param array<int, array<string, bool>> $storedKeys
     */
    protected function deleteObsoleteStorageEntries(
        array $existingStorageEntitiesByProductAbstractIdAndLocale,
        array $storedKeys,
    ): void {
        foreach ($existingStorageEntitiesByProductAbstractIdAndLocale as $idProductAbstract => $storageEntitiesByLocale) {
            $this->deleteObsoleteStorageEntriesByLocale($storageEntitiesByLocale, $storedKeys, $idProductAbstract);
        }
    }

    /**
     * @param array<string, \Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage> $storageEntitiesByLocale
     * @param array<int, array<string, bool>> $storedKeys
     * @param int $idProductAbstract
     */
    protected function deleteObsoleteStorageEntriesByLocale(
        array $storageEntitiesByLocale,
        array $storedKeys,
        int $idProductAbstract,
    ): void {
        foreach ($storageEntitiesByLocale as $localeName => $storageEntity) {
            if (isset($storedKeys[$idProductAbstract][$localeName])) {
                continue;
            }

            $this->remove($storageEntity);
        }
    }
}
