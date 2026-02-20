<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttachmentStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductAttachmentStorage\Expander\ProductViewExpander;
use Spryker\Client\ProductAttachmentStorage\Expander\ProductViewExpanderInterface;
use Spryker\Client\ProductAttachmentStorage\Storage\ProductAbstractAttachmentStorageReader;
use Spryker\Client\ProductAttachmentStorage\Storage\ProductAbstractAttachmentStorageReaderInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class ProductAttachmentStorageFactory extends AbstractFactory
{
    public function createProductViewExpander(): ProductViewExpanderInterface
    {
        return new ProductViewExpander(
            $this->createProductAbstractAttachmentStorageReader(),
        );
    }

    public function createProductAbstractAttachmentStorageReader(): ProductAbstractAttachmentStorageReaderInterface
    {
        return new ProductAbstractAttachmentStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService(),
        );
    }

    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(ProductAttachmentStorageDependencyProvider::CLIENT_STORAGE);
    }

    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductAttachmentStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductAttachmentStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
