<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductAttachment\Persistence\Map\SpyProductAttachmentProductAbstractTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttachmentStorage\Communication\ProductAttachmentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageConfig getConfig()
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageBusinessFactory getBusinessFactory()
 */
class ProductAttachmentProductAbstractStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys(
                $eventEntityTransfers,
                SpyProductAttachmentProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT,
            );

        if ($productAbstractIds === []) {
            return;
        }

        $this->getBusinessFactory()
            ->createProductAbstractAttachmentStorageWriter()
            ->publish($productAbstractIds);
    }
}
