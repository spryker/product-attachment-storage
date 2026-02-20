<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Persistence;

use Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstractQuery;
use Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageConfig getConfig()
 */
class ProductAttachmentStoragePersistenceFactory extends AbstractPersistenceFactory
{
    public function createProductAbstractAttachmentStorageQuery(): SpyProductAbstractAttachmentStorageQuery
    {
        return SpyProductAbstractAttachmentStorageQuery::create();
    }

    public function getProductAttachmentProductAbstractQuery(): SpyProductAttachmentProductAbstractQuery
    {
        return $this->getProvidedDependency(ProductAttachmentStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ATTACHMENT_PRODUCT_ABSTRACT);
    }
}
