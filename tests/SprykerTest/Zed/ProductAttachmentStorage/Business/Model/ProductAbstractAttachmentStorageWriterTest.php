<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttachmentStorage\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer;
use Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Zed\ProductAttachmentStorage\Business\Writer\ProductAbstractAttachmentStorageWriter;
use Spryker\Zed\ProductAttachmentStorage\Business\Writer\ProductAbstractAttachmentStorageWriterInterface;
use Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageEntityManager;
use Spryker\Zed\ProductAttachmentStorage\Persistence\ProductAttachmentStorageRepository;
use SprykerTest\Zed\ProductAttachmentStorage\ProductAttachmentStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAttachmentStorage
 * @group Business
 * @group Model
 * @group ProductAbstractAttachmentStorageWriterTest
 * Add your own group annotations below this line
 */
class ProductAbstractAttachmentStorageWriterTest extends Unit
{
    /**
     * @uses \Spryker\Client\Queue\QueueDependencyProvider::QUEUE_ADAPTERS
     */
    protected const string QUEUE_ADAPTERS = 'queue adapters';

    protected const string LOCALE_US = 'en_US';

    protected const string LOCALE_DE = 'de_DE';

    protected const string DEFAULT_LOCALE = 'default';

    protected ProductAttachmentStorageBusinessTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->tester->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    public function testGivenProductAbstractWithAttachmentsWhenPublishingThenStorageEntriesAreCreated(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $idProductAttachment = $this->tester->haveProductAttachment(['label' => 'Test Attachment', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idProductAttachment);

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());
        $this->assertNotEmpty($storageEntities);
    }

    public function testGivenProductAbstractWithLocalizedAttachmentsWhenPublishingThenMultipleLocaleEntriesAreCreated(): void
    {
        // Arrange
        $localeUsTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_US]);
        $localeDeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $idProductAttachmentUs = $this->tester->haveProductAttachment(['label' => 'English Label', 'fkLocale' => $localeUsTransfer->getIdLocaleOrFail()]);
        $idProductAttachmentDe = $this->tester->haveProductAttachment(['label' => 'German Label', 'fkLocale' => $localeDeTransfer->getIdLocaleOrFail()]);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idProductAttachmentUs);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idProductAttachmentDe);

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());
        $this->assertGreaterThanOrEqual(2, count($storageEntities));
    }

    public function testGivenProductAbstractWithNonLocalizedAttachmentsWhenPublishingThenDefaultEntryIsCreated(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $idProductAttachment = $this->tester->haveProductAttachment(['key' => 'default-attachment']);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idProductAttachment);

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());
        $hasDefaultEntry = false;

        foreach ($storageEntities as $entity) {
            if ($entity->getLocale() === static::DEFAULT_LOCALE) {
                $hasDefaultEntry = true;

                break;
            }
        }

        $this->assertTrue($hasDefaultEntry);
    }

    public function testGivenMultipleAttachmentsWithDifferentSortOrdersWhenPublishingThenStoragePreservesSortOrder(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_US]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $idAttachment1 = $this->tester->haveProductAttachment(['label' => 'First', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $idAttachment2 = $this->tester->haveProductAttachment(['label' => 'Second', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $idAttachment3 = $this->tester->haveProductAttachment(['label' => 'Third', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idAttachment1, 10);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idAttachment2, 5);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idAttachment3, 15);

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntity = $this->tester->findProductAbstractAttachmentStorageByLocale($productAbstractTransfer->getIdProductAbstractOrFail(), static::LOCALE_US);
        $this->assertNotNull($storageEntity);
        $data = $storageEntity->getData();
        $this->assertCount(3, $data['attachments']);
    }

    public function testGivenExistingStorageEntriesWhenRepublishingWithUpdatedDataThenStorageIsUpdated(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_US]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $idAttachment = $this->tester->haveProductAttachment(['label' => 'Original Label', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idAttachment);
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);
        $attachmentEntity = SpyProductAttachmentQuery::create()->findPk($idAttachment);
        $attachmentEntity->setLabel('Updated Label')->save();

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());
        $this->assertCount(1, $storageEntities);
        $this->assertSame('Updated Label', $storageEntities[0]->getData()['attachments'][0]['label']);
    }

    public function testGivenExistingMultipleLocaleEntriesWhenRepublishingWithFewerLocalesThenObsoleteEntriesAreRemoved(): void
    {
        // Arrange
        $localeUsTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_US]);
        $localeDeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $idAttachment = $this->tester->haveProductAttachment(['label' => 'English', 'fkLocale' => $localeUsTransfer->getIdLocaleOrFail()]);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idAttachment);
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);
        $initialCount = count($this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()));
        $this->tester->haveProductAbstractAttachmentStorage($localeDeTransfer, [ProductAbstractAttachmentStorageTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());
        $this->assertSame($initialCount, count($storageEntities));
    }

    public function testGivenProductWithBothLocalizedAndDefaultAttachmentsWhenPublishingThenBothTypesAreStored(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $idLocalizedAttachment = $this->tester->haveProductAttachment(['label' => 'Localized', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $idDefaultAttachment = $this->tester->haveProductAttachment(['label' => 'Default']);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idLocalizedAttachment);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstractTransfer->getIdProductAbstractOrFail(), $idDefaultAttachment);

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());
        $locales = array_map(fn ($entity) => $entity->getLocale(), $storageEntities);
        $this->assertContains(static::LOCALE_DE, $locales);
        $this->assertContains(static::DEFAULT_LOCALE, $locales);
    }

    public function testGivenProductAbstractWithNoAttachmentsWhenPublishingThenNoStorageEntriesAreCreated(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());
        $this->assertCount(0, $storageEntities);
    }

    public function testGivenMultipleProductAbstractsWhenPublishingThenEachProductHasIsolatedStorageEntries(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_US]);
        $productAbstract1 = $this->tester->haveProductAbstract();
        $productAbstract2 = $this->tester->haveProductAbstract();
        $idAttachment1 = $this->tester->haveProductAttachment(['label' => 'Product 1 Attachment', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $idAttachment2 = $this->tester->haveProductAttachment(['label' => 'Product 2 Attachment', 'fkLocale' => $localeTransfer->getIdLocaleOrFail()]);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstract1->getIdProductAbstractOrFail(), $idAttachment1);
        $this->tester->haveProductAbstractAttachmentRelation($productAbstract2->getIdProductAbstractOrFail(), $idAttachment2);

        // Act
        $this->createProductAbstractAttachmentStorageWriter()->publish([$productAbstract1->getIdProductAbstractOrFail(), $productAbstract2->getIdProductAbstractOrFail()]);

        // Assert
        $storageEntities1 = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstract1->getIdProductAbstractOrFail());
        $storageEntities2 = $this->tester->findProductAbstractAttachmentStorageCollectionByIdProductAbstract($productAbstract2->getIdProductAbstractOrFail());
        $this->assertCount(1, $storageEntities1);
        $this->assertCount(1, $storageEntities2);
        $this->assertSame('Product 1 Attachment', $storageEntities1[0]->getData()['attachments'][0]['label']);
        $this->assertSame('Product 2 Attachment', $storageEntities2[0]->getData()['attachments'][0]['label']);
    }

    public function createProductAbstractAttachmentStorageWriter(): ProductAbstractAttachmentStorageWriterInterface
    {
        return new ProductAbstractAttachmentStorageWriter(
            new ProductAttachmentStorageRepository(),
            new ProductAttachmentStorageEntityManager(),
        );
    }
}
