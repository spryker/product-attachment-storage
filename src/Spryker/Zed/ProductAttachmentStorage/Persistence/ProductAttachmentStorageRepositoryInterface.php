<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Persistence;

use Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorageQuery;

interface ProductAttachmentStorageRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstract>
     */
    public function getProductAttachmentsProductAbstractEntitiesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, array<string, \Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage>>
     */
    public function getProductAbstractAttachmentStorageEntitiesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $attachmentIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByAttachmentIds(array $attachmentIds): array;

    /**
     * @param array<int> $productAbstractIds
     */
    public function queryProductAbstractAttachmentStorageByIds(array $productAbstractIds): SpyProductAbstractAttachmentStorageQuery;
}
