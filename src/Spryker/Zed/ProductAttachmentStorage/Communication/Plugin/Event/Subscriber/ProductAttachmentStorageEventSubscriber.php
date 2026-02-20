<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAttachment\Dependency\ProductAttachmentEvents;
use Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Event\Listener\ProductAttachmentProductAbstractStoragePublishListener;
use Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Event\Listener\ProductAttachmentProductAbstractStorageUnpublishListener;
use Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Event\Listener\ProductAttachmentStoragePublishListener;
use Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Event\Listener\ProductAttachmentStorageUnpublishListener;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\Communication\ProductAttachmentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageConfig getConfig()
 */
class ProductAttachmentStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addProductAttachmentCreateListener($eventCollection);
        $this->addProductAttachmentUpdateListener($eventCollection);
        $this->addProductAttachmentDeleteListener($eventCollection);
        $this->addProductAttachmentProductAbstractCreateListener($eventCollection);
        $this->addProductAttachmentProductAbstractUpdateListener($eventCollection);
        $this->addProductAttachmentProductAbstractDeleteListener($eventCollection);
        $this->addProductAttachmentPublishListener($eventCollection);

        return $eventCollection;
    }

    protected function addProductAttachmentCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductAttachmentEvents::ENTITY_SPY_PRODUCT_ATTACHMENT_CREATE,
            new ProductAttachmentStoragePublishListener(),
            0,
            null,
            $this->getConfig()->getProductAttachmentEventQueueName(),
        );
    }

    protected function addProductAttachmentPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductAttachmentEvents::PRODUCT_ABSTRACT_ATTACHMENT_PUBLISH,
            new ProductAttachmentStoragePublishListener(),
            0,
            null,
            $this->getConfig()->getProductAttachmentEventQueueName(),
        );
    }

    protected function addProductAttachmentUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductAttachmentEvents::ENTITY_SPY_PRODUCT_ATTACHMENT_UPDATE,
            new ProductAttachmentStoragePublishListener(),
            0,
            null,
            $this->getConfig()->getProductAttachmentEventQueueName(),
        );
    }

    protected function addProductAttachmentDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductAttachmentEvents::ENTITY_SPY_PRODUCT_ATTACHMENT_DELETE,
            new ProductAttachmentStorageUnpublishListener(),
            0,
            null,
            $this->getConfig()->getProductAttachmentEventQueueName(),
        );
    }

    protected function addProductAttachmentProductAbstractCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductAttachmentEvents::ENTITY_SPY_PRODUCT_ATTACHMENT_PRODUCT_ABSTRACT_CREATE,
            new ProductAttachmentProductAbstractStoragePublishListener(),
            0,
            null,
            $this->getConfig()->getProductAttachmentEventQueueName(),
        );
    }

    protected function addProductAttachmentProductAbstractUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductAttachmentEvents::ENTITY_SPY_PRODUCT_ATTACHMENT_PRODUCT_ABSTRACT_UPDATE,
            new ProductAttachmentProductAbstractStoragePublishListener(),
            0,
            null,
            $this->getConfig()->getProductAttachmentEventQueueName(),
        );
    }

    protected function addProductAttachmentProductAbstractDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductAttachmentEvents::ENTITY_SPY_PRODUCT_ATTACHMENT_PRODUCT_ABSTRACT_DELETE,
            new ProductAttachmentProductAbstractStorageUnpublishListener(),
            0,
            null,
            $this->getConfig()->getProductAttachmentEventQueueName(),
        );
    }
}
