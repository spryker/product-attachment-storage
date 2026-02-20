<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttachmentStorage;

use Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer;
use Generated\Shared\Transfer\ProductAttachmentStorageCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductAttachmentStorage\ProductAttachmentStorageFactory getFactory()
 */
class ProductAttachmentStorageClient extends AbstractClient implements ProductAttachmentStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findProductAbstractAttachmentStorage(
        ProductAttachmentStorageCriteriaTransfer $productAttachmentStorageCriteriaTransfer,
    ): ?ProductAbstractAttachmentStorageTransfer {
        return $this->getFactory()
            ->createProductAbstractAttachmentStorageReader()
            ->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);
    }
}
