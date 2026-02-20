<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductAttachmentStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAttachmentStorageCriteriaTransfer;
use Spryker\Client\ProductAttachmentStorage\ProductAttachmentStorageDependencyProvider;
use Spryker\Client\Storage\StorageClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductAttachmentStorage
 * @group ProductAttachmentStorageClientTest
 * Add your own group annotations below this line
 */
class ProductAttachmentStorageClientTest extends Unit
{
    protected const int PRODUCT_ABSTRACT_ID = 123;

    protected const string LOCALE_EN = 'en_us';

    protected const string LOCALE_DE = 'de_de';

    protected const string DEFAULT_LOCALE = 'default';

    protected ProductAttachmentStorageClientTester $tester;

    public function testGivenProductWithLocalizedAttachmentsWhenFindingByLocaleThenReturnsAttachments(): void
    {
        // Arrange
        $attachments = [
            ['idProductAttachment' => 1, 'label' => 'Manual', 'url' => '/manual.pdf', 'sortOrder' => 10],
        ];
        $storageData = [
            'idProductAbstract' => static::PRODUCT_ABSTRACT_ID,
            'attachments' => $attachments,
        ];
        $this->setStorageClientMock(['kv:product_abstract_attachment:en_us:123' => json_encode($storageData)]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_EN);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNotNull($result);
        $this->assertSame(static::PRODUCT_ABSTRACT_ID, $result->getIdProductAbstract());
        $this->assertCount(1, $result->getAttachments());
    }

    public function testGivenProductWithDefaultAttachmentsOnlyWhenFindingByLocaleThenReturnsDefaultAttachments(): void
    {
        // Arrange
        $attachments = [
            ['idProductAttachment' => 1, 'label' => 'Default Manual', 'url' => '/manual.pdf', 'sortOrder' => 10],
        ];
        $storageData = [
            'idProductAbstract' => static::PRODUCT_ABSTRACT_ID,
            'attachments' => $attachments,
        ];
        $this->setStorageClientMock(['kv:product_abstract_attachment:default:123' => json_encode($storageData)]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_EN);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getAttachments());
        $this->assertSame('Default Manual', $result->getAttachments()[0]->getLabel());
    }

    public function testGivenNonExistentProductWhenFindingByLocaleThenReturnsNull(): void
    {
        // Arrange
        $this->setStorageClientMock([]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_EN);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNull($result);
    }

    public function testGivenProductWithBothLocalizedAndDefaultAttachmentsWhenFindingByLocaleThenMergesBothCorrectly(): void
    {
        // Arrange
        $localizedAttachments = [
            ['idProductAttachment' => 1, 'label' => 'Deutsche Anleitung', 'url' => '/anleitung.pdf', 'sortOrder' => 5],
        ];
        $defaultAttachments = [
            ['idProductAttachment' => 2, 'label' => 'Manual', 'url' => '/manual.pdf', 'sortOrder' => 10],
            ['idProductAttachment' => 3, 'label' => 'Guide', 'url' => '/guide.pdf', 'sortOrder' => 20],
        ];
        $this->setStorageClientMock([
            'kv:product_abstract_attachment:de_de:123' => json_encode(['idProductAbstract' => static::PRODUCT_ABSTRACT_ID, 'attachments' => $localizedAttachments]),
            'kv:product_abstract_attachment:default:123' => json_encode(['idProductAbstract' => static::PRODUCT_ABSTRACT_ID, 'attachments' => $defaultAttachments]),
        ]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_DE);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getAttachments());
    }

    public function testGivenRequestForDefaultLocaleWhenFindingThenReturnsOnlyDefaultAttachments(): void
    {
        // Arrange
        $localizedAttachments = [
            ['idProductAttachment' => 1, 'label' => 'Localized', 'url' => '/loc.pdf', 'sortOrder' => 5],
        ];
        $defaultAttachments = [
            ['idProductAttachment' => 2, 'label' => 'Default', 'url' => '/def.pdf', 'sortOrder' => 10],
        ];
        $this->setStorageClientMock([
            'kv:product_abstract_attachment:default:123' => json_encode(['idProductAbstract' => static::PRODUCT_ABSTRACT_ID, 'attachments' => $defaultAttachments]),
            'kv:product_abstract_attachment:de_de:123' => json_encode(['idProductAbstract' => static::PRODUCT_ABSTRACT_ID, 'attachments' => $localizedAttachments]),
        ]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::DEFAULT_LOCALE);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getAttachments());
        $this->assertSame('Default', $result->getAttachments()[0]->getLabel());
    }

    public function testGivenAttachmentsWithDifferentSortOrdersWhenFindingThenReturnsSortedByAscendingSortOrder(): void
    {
        // Arrange
        $attachments = [
            ['idProductAttachment' => 1, 'label' => 'Third', 'url' => '/third.pdf', 'sortOrder' => 20],
            ['idProductAttachment' => 2, 'label' => 'Second', 'url' => '/second.pdf', 'sortOrder' => 10],
            ['idProductAttachment' => 3, 'label' => 'First', 'url' => '/first.pdf', 'sortOrder' => 5],
            ['idProductAttachment' => 4, 'label' => 'No Order', 'url' => '/none.pdf', 'sortOrder' => null],
        ];
        $this->setStorageClientMock([
            'kv:product_abstract_attachment:en_us:123' => json_encode(['idProductAbstract' => static::PRODUCT_ABSTRACT_ID, 'attachments' => $attachments]),
        ]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_EN);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNotNull($result);
        $this->assertSame('No Order', $result->getAttachments()[0]->getLabel());
        $this->assertSame('First', $result->getAttachments()[1]->getLabel());
        $this->assertSame('Second', $result->getAttachments()[2]->getLabel());
        $this->assertSame('Third', $result->getAttachments()[3]->getLabel());
    }

    public function testGivenMergedAttachmentsWithMixedSortOrdersWhenFindingThenSortsCombinedList(): void
    {
        // Arrange
        $localizedAttachments = [
            ['idProductAttachment' => 1, 'label' => 'Localized', 'url' => '/loc.pdf', 'sortOrder' => 10],
        ];
        $defaultAttachments = [
            ['idProductAttachment' => 2, 'label' => 'Default First', 'url' => '/def1.pdf', 'sortOrder' => 5],
            ['idProductAttachment' => 3, 'label' => 'Default Second', 'url' => '/def2.pdf', 'sortOrder' => 15],
        ];
        $this->setStorageClientMock([
            'kv:product_abstract_attachment:en_us:123' => json_encode(['idProductAbstract' => static::PRODUCT_ABSTRACT_ID, 'attachments' => $localizedAttachments]),
            'kv:product_abstract_attachment:default:123' => json_encode(['idProductAbstract' => static::PRODUCT_ABSTRACT_ID, 'attachments' => $defaultAttachments]),
        ]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_EN);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getAttachments());
        $this->assertSame(5, $result->getAttachments()[0]->getSortOrder());
        $this->assertSame(10, $result->getAttachments()[1]->getSortOrder());
        $this->assertSame(15, $result->getAttachments()[2]->getSortOrder());
    }

    public function testGivenEmptyAttachmentsArrayWhenFindingThenReturnsEmptyArrayObject(): void
    {
        // Arrange
        $storageData = [
            'idProductAbstract' => static::PRODUCT_ABSTRACT_ID,
            'attachments' => [],
        ];
        $this->setStorageClientMock([
            'kv:product_abstract_attachment:en_us:123' => json_encode($storageData),
        ]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_EN);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(0, $result->getAttachments());
    }

    public function testGivenMalformedStorageDataWhenFindingThenReturnsNull(): void
    {
        // Arrange
        $this->setStorageClientMock([
            'kv:product_abstract_attachment:en_us:123' => 'invalid-json{',
        ]);

        $productAttachmentStorageCriteriaTransfer = (new ProductAttachmentStorageCriteriaTransfer())
            ->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID)
            ->setLocale(static::LOCALE_EN);

        // Act
        $result = $this->tester->getClient()->findProductAbstractAttachmentStorage($productAttachmentStorageCriteriaTransfer);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @param array<string, string|null> $storageData
     */
    protected function setStorageClientMock(array $storageData): void
    {
        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock->method('getMulti')
            ->willReturnCallback(function (array $keys) use ($storageData) {
                $result = [];

                foreach ($keys as $key) {
                    $fullKey = 'kv:' . $key;

                    if (isset($storageData[$fullKey])) {
                        $result[$fullKey] = $storageData[$fullKey];
                    }
                }

                return $result;
            });

        $this->tester->setDependency(ProductAttachmentStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }
}
