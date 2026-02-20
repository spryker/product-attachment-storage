<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Persistence;

use Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStoragePersistenceFactory getFactory()
 *
 * @module ProductAttachment
 */
class ProductAttachmentStorageRepository extends AbstractRepository implements ProductAttachmentStorageRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstract>
     */
    public function getProductAttachmentsProductAbstractEntitiesByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductAttachmentProductAbstractQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->innerJoinWithProductAttachment()
            ->useProductAttachmentQuery()
                ->leftJoinWithLocale()
            ->endUse()
            ->orderBy('order', Criteria::ASC)
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, array<string, \Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage>>
     */
    public function getProductAbstractAttachmentStorageEntitiesByProductAbstractIds(array $productAbstractIds): array
    {
        $storageEntities = $this->getFactory()
            ->createProductAbstractAttachmentStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        $groupedEntities = [];

        foreach ($storageEntities as $entity) {
            $groupedEntities[$entity->getFkProductAbstract()][$entity->getLocale()] = $entity;
        }

        return $groupedEntities;
    }

    /**
     * @param array<int> $attachmentIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByAttachmentIds(array $attachmentIds): array
    {
        return $this->getFactory()
            ->getProductAttachmentProductAbstractQuery()
            ->filterByFkProductAttachment_In($attachmentIds)
            ->select(['fk_product_abstract'])
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $productAbstractIds
     */
    public function queryProductAbstractAttachmentStorageByIds(array $productAbstractIds): SpyProductAbstractAttachmentStorageQuery
    {
        $query = $this->getFactory()->createProductAbstractAttachmentStorageQuery();

        if ($productAbstractIds !== []) {
            $query->filterByFkProductAbstract_In($productAbstractIds);
        }

        return $query;
    }
}
