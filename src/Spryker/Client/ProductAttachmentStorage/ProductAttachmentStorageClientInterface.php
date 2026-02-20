<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttachmentStorage;

use Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer;
use Generated\Shared\Transfer\ProductAttachmentStorageCriteriaTransfer;

interface ProductAttachmentStorageClientInterface
{
    /**
     * Specification:
     * - Requires `ProductAttachmentStorageCriteriaTransfer.idProductAbstract` to be set.
     * - Requires `ProductAttachmentStorageCriteriaTransfer.locale` to be set.
     * - Fetches product abstract attachment data from storage.
     * - Uses mget to fetch locale-specific and default keys.
     * - Prefers locale-specific data over default.
     * - Returns null if no data found.
     *
     * @api
     */
    public function findProductAbstractAttachmentStorage(
        ProductAttachmentStorageCriteriaTransfer $productAttachmentStorageCriteriaTransfer,
    ): ?ProductAbstractAttachmentStorageTransfer;
}
