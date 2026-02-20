<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttachmentStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer;
use Generated\Shared\Transfer\ProductAttachmentStorageCriteriaTransfer;

interface ProductAbstractAttachmentStorageReaderInterface
{
    public function findProductAbstractAttachmentStorage(
        ProductAttachmentStorageCriteriaTransfer $productAttachmentStorageCriteriaTransfer,
    ): ?ProductAbstractAttachmentStorageTransfer;
}
