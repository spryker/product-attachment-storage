<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStoragePersistenceFactory getFactory()
 */
class ProductAttachmentStorageEntityManager extends AbstractEntityManager implements ProductAttachmentStorageEntityManagerInterface
{
    /**
     * @param array<int> $productAbstractIds
     */
    public function deleteProductAbstractAttachmentStorageByProductAbstractIds(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductAbstractAttachmentStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->delete();
    }
}
