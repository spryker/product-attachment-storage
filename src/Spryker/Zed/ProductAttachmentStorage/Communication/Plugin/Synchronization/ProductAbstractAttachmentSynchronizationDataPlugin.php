<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage\Communication\Plugin\Synchronization;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\ProductAttachmentStorage\ProductAttachmentStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

/**
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttachmentStorage\Communication\ProductAttachmentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttachmentStorage\ProductAttachmentStorageConfig getConfig()
 * @method \Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageRepositoryInterface getRepository()
 */
class ProductAbstractAttachmentSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataQueryContainerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductAttachmentStorageConfig::PRODUCT_ABSTRACT_ATTACHMENT_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $ids
     */
    public function queryData($ids = []): ?ModelCriteria
    {
        $query = $this->getRepository()->queryProductAbstractAttachmentStorageByIds($ids);

        if ($ids === []) {
            $query->clear();
        }

        return $query->orderByIdProductAbstractAttachmentStorage();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ProductAttachmentStorageConfig::PRODUCT_ABSTRACT_ATTACHMENT_SYNC_STORAGE_QUEUE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getProductAttachmentSynchronizationPoolName();
    }
}
