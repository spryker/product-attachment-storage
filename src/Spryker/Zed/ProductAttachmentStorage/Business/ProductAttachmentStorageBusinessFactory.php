<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAttachmentStorage\Business\Writer\ProductAbstractAttachmentStorageWriter;
use Spryker\Zed\ProductAttachmentStorage\Business\Writer\ProductAbstractAttachmentStorageWriterInterface;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageConfig getConfig()
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageEntityManagerInterface getEntityManager()
 */
class ProductAttachmentStorageBusinessFactory extends AbstractBusinessFactory
{
    public function createProductAbstractAttachmentStorageWriter(): ProductAbstractAttachmentStorageWriterInterface
    {
        return new ProductAbstractAttachmentStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }
}
