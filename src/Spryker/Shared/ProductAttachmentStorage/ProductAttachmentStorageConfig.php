<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductAttachmentStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductAttachmentStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name, this will use it for key generating
     *
     * @api
     */
    public const string PRODUCT_ABSTRACT_ATTACHMENT_RESOURCE_NAME = 'product_abstract_attachment';

    /**
     * Specification:
     * - Queue name as used for processing attachment sync messages
     *
     * @api
     */
    public const string PRODUCT_ABSTRACT_ATTACHMENT_SYNC_STORAGE_QUEUE = 'sync.storage.product_attachment';

    /**
     * Specification:
     * - Defines queue name for publish.
     *
     * @api
     */
    public const string PUBLISH_PRODUCT_ABSTRACT_ATTACHMENT = 'publish.product_attachment_abstract';
}
