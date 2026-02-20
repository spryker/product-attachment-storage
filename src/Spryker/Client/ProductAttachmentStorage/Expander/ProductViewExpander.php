<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttachmentStorage\Expander;

use Generated\Shared\Transfer\ProductAttachmentStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductAttachmentStorage\Storage\ProductAbstractAttachmentStorageReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    public function __construct(
        protected ProductAbstractAttachmentStorageReaderInterface $productAbstractAttachmentStorageReader,
    ) {
    }

    public function expandProductViewWithAttachments(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        string $localeName,
    ): ProductViewTransfer {
        $idProductAbstract = $productViewTransfer->getIdProductAbstract();

        if (!$idProductAbstract) {
            return $productViewTransfer;
        }

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract($idProductAbstract)
            ->setLocale($localeName);

        $productAbstractAttachmentStorageTransfer = $this->productAbstractAttachmentStorageReader
            ->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        if (!$productAbstractAttachmentStorageTransfer) {
            return $productViewTransfer;
        }

        return $productViewTransfer->setAttachments($productAbstractAttachmentStorageTransfer->getAttachments());
    }
}
