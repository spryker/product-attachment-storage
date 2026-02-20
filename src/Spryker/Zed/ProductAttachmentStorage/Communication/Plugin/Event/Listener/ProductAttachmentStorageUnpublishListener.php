<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttachmentStorage\Communication\ProductAttachmentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageConfig getConfig()
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageBusinessFactory getBusinessFactory()
 */
class ProductAttachmentStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $attachmentIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventEntityTransfers);

        if ($attachmentIds === []) {
            return;
        }

        $productAbstractIds = $this->getRepository()->getProductAbstractIdsByAttachmentIds($attachmentIds);

        if ($productAbstractIds === []) {
            return;
        }

        $this->getBusinessFactory()
            ->createProductAbstractAttachmentStorageWriter()
            ->publish($productAbstractIds);
    }
}
