<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Communication;

use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageConfig getConfig()
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageEntityManagerInterface getEntityManager()
 */
class ProductAttachmentStorageCommunicationFactory extends AbstractCommunicationFactory
{
    public function getEventBehaviorFacade(): EventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttachmentStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
