<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttachmentStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductAbstractAttachmentStorageBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer;
use Orm\Zed\ProductAttachment\Persistence\SpyProductAttachment;
use Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstract;
use Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage;
use Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorageQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ProductAttachmentStorage\Business\ProductAttachmentStorageFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductAttachmentStorage\PHPMD)
 */
class ProductAttachmentStorageBusinessTester extends Actor
{
    use _generated\ProductAttachmentStorageBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAttachmentStorageTransfer
     */
    public function haveProductAbstractAttachmentStorage(LocaleTransfer $localeTransfer, array $seedData = []): ProductAbstractAttachmentStorageTransfer
    {
        $productAbstractAttachmentStorageTransfer = (new ProductAbstractAttachmentStorageBuilder($seedData))->build();

        $productAbstractAttachmentStorageEntity = new SpyProductAbstractAttachmentStorage();
        $productAbstractAttachmentStorageEntity->setFkProductAbstract($productAbstractAttachmentStorageTransfer->getIdProductAbstractOrFail());
        $productAbstractAttachmentStorageEntity->setLocale($localeTransfer->getLocaleNameOrFail());
        $productAbstractAttachmentStorageEntity->setData($productAbstractAttachmentStorageTransfer->toArray());
        $productAbstractAttachmentStorageEntity->save();

        return $productAbstractAttachmentStorageTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return list<\Orm\Zed\ProductAttachmentStorage\Persistence\SpyProductAbstractAttachmentStorage>
     */
    public function findProductAbstractAttachmentStorageCollectionByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getProductAbstractAttachmentStorageQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->find()
            ->getData();
    }

    protected function getProductAbstractAttachmentStorageQuery(): SpyProductAbstractAttachmentStorageQuery
    {
        return SpyProductAbstractAttachmentStorageQuery::create();
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return int
     */
    public function haveProductAttachment(array $data = []): int
    {
        $productAttachmentEntity = new SpyProductAttachment();
        $productAttachmentEntity->setLabel($data['label'] ?? 'Test Label');
        $productAttachmentEntity->setUrl($data['url'] ?? 'https://example.com/test.pdf');

        if (isset($data['fkLocale'])) {
            $productAttachmentEntity->setFkLocale($data['fkLocale']);
        }

        $productAttachmentEntity->save();

        return $productAttachmentEntity->getIdProductAttachment();
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductAttachment
     * @param int $order
     *
     * @return void
     */
    public function haveProductAbstractAttachmentRelation(int $idProductAbstract, int $idProductAttachment, int $order = 0): void
    {
        $entity = new SpyProductAttachmentProductAbstract();
        $entity->setFkProductAbstract($idProductAbstract);
        $entity->setFkProductAttachment($idProductAttachment);
        $entity->setOrder($order);
        $entity->save();
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     */
    public function findProductAbstractAttachmentStorageByLocale(int $idProductAbstract, string $locale): ?SpyProductAbstractAttachmentStorage
    {
        return $this->getProductAbstractAttachmentStorageQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByLocale($locale)
            ->findOne();
    }
}
