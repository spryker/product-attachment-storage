<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAttachmentStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the name of the product attachment synchronization pool.
     *
     * @api
     */
    public function getProductAttachmentSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Returns the name of the product attachment event queue.
     *
     * @api
     */
    public function getProductAttachmentEventQueueName(): ?string
    {
        return null;
    }
}
