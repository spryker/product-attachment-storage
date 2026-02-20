<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttachmentStorage;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\ProductAttachment\Persistence\SpyProductAttachmentProductAbstractQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductAttachmentStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    public const string PROPEL_QUERY_LOCALE = 'PROPEL_QUERY_LOCALE';

    public const string PROPEL_QUERY_PRODUCT_ATTACHMENT_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ATTACHMENT_PRODUCT_ABSTRACT';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);

        return $container;
    }

    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addLocaleQuery($container);
        $container = $this->addProductAttachmentProductAbstractQuery($container);

        return $container;
    }

    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return $container->getLocator()->eventBehavior()->facade();
        });

        return $container;
    }

    protected function addLocaleQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_LOCALE, $container->factory(function () {
            return SpyLocaleQuery::create();
        }));

        return $container;
    }

    protected function addProductAttachmentProductAbstractQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_ATTACHMENT_PRODUCT_ABSTRACT, $container->factory(function () {
            return SpyProductAttachmentProductAbstractQuery::create();
        }));

        return $container;
    }
}
