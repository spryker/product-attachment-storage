<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttachmentStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductAttachmentStorage\ProductAttachmentStorageFactory getFactory()
 * @method \Spryker\Client\ProductAttachmentStorage\ProductAttachmentStorageClientInterface getClient()
 */
class ProductAttachmentProductViewExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * @api
     * - Expand ProductViewTransfer with attachments.
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<string, mixed> $productData
     * @param string $localeName
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName): ProductViewTransfer
    {
        return $this->getFactory()
            ->createProductViewExpander()
            ->expandProductViewWithAttachments($productViewTransfer, $productData, $localeName);
    }
}
