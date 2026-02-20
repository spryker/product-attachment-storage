<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttachmentStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer;
use Generated\Shared\Transfer\ProductAttachmentStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductAttachmentStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\ProductAttachmentStorage\ProductAttachmentStorageConfig;

class ProductAbstractAttachmentStorageReader implements ProductAbstractAttachmentStorageReaderInterface
{
    protected const string DEFAULT_LOCALE = 'default';

    protected const string KV_PREFIX = 'kv:';

    public function __construct(
        protected StorageClientInterface $storageClient,
        protected SynchronizationServiceInterface $synchronizationService,
        protected UtilEncodingServiceInterface $utilEncodingService,
    ) {
    }

    public function findProductAbstractAttachmentStorage(
        ProductAttachmentStorageCriteriaTransfer $productAttachmentStorageCriteriaTransfer,
    ): ?ProductAbstractAttachmentStorageTransfer {
        $idProductAbstract = $productAttachmentStorageCriteriaTransfer->getIdProductAbstractOrFail();
        $locale = $productAttachmentStorageCriteriaTransfer->getLocaleOrFail();

        $defaultKey = $this->generateKey($idProductAbstract, static::DEFAULT_LOCALE);
        $localeKey = $this->generateKey($idProductAbstract, $locale);

        $storageData = $this->storageClient->getMulti([$defaultKey, $localeKey]);
        $defaultData = $this->decodeStorageData($storageData[static::KV_PREFIX . $defaultKey] ?? null);
        $localizedData = $this->decodeStorageData($storageData[static::KV_PREFIX . $localeKey] ?? null);

        if ($defaultData === null && $localizedData === null) {
            return null;
        }

        $defaultAttachments = $defaultData['attachments'] ?? [];
        $localizedAttachments = $localizedData['attachments'] ?? [];

        if ($locale === static::DEFAULT_LOCALE) {
            $localizedAttachments = [];
        }

        $mergedAttachments = $this->mergeLocalizedWithDefaultAttachments($localizedAttachments, $defaultAttachments);
        $attachmentTransfers = $this->mapAttachmentsToTransfers($mergedAttachments);
        $attachmentTransfers = $this->sortAttachmentsBySortOrder($attachmentTransfers);

        return (new ProductAbstractAttachmentStorageTransfer())
            ->setIdProductAbstract($idProductAbstract)
            ->setAttachments(new ArrayObject($attachmentTransfers));
    }

    /**
     * @param array<array<string, mixed>> $localizedAttachments
     * @param array<array<string, mixed>> $defaultAttachments
     *
     * @return array<array<string, mixed>>
     */
    protected function mergeLocalizedWithDefaultAttachments(array $localizedAttachments, array $defaultAttachments): array
    {
        return [...$localizedAttachments, ...$defaultAttachments];
    }

    /**
     * @param array<array<string, mixed>> $attachments
     *
     * @return array<\Generated\Shared\Transfer\ProductAttachmentStorageTransfer>
     */
    protected function mapAttachmentsToTransfers(array $attachments): array
    {
        $attachmentTransfers = [];

        foreach ($attachments as $attachmentData) {
            $attachmentTransfers[] = (new ProductAttachmentStorageTransfer())->fromArray($attachmentData, true);
        }

        return $attachmentTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAttachmentStorageTransfer> $attachmentTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAttachmentStorageTransfer>
     */
    protected function sortAttachmentsBySortOrder(array $attachmentTransfers): array
    {
        usort(
            $attachmentTransfers,
            function (ProductAttachmentStorageTransfer $a, ProductAttachmentStorageTransfer $b) {
                return ($a->getSortOrder() ?? 0) <=> ($b->getSortOrder() ?? 0);
            },
        );

        return $attachmentTransfers;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function decodeStorageData(?string $data): ?array
    {
        if ($data === null) {
            return null;
        }

        $decodedData = $this->utilEncodingService->decodeJson($data, true);

        return is_array($decodedData) ? $decodedData : null;
    }

    protected function generateKey(int $idProductAbstract, string $locale): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference((string)$idProductAbstract)
            ->setLocale($locale);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductAttachmentStorageConfig::PRODUCT_ABSTRACT_ATTACHMENT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
