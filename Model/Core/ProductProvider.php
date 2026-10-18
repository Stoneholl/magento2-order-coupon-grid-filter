<?php

declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Model\Core;

use Stoneholl\Core\Api\ProductProviderInterface;
use Stoneholl\Core\Model\Data\ProductDescriptor;

class ProductProvider implements ProductProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getProducts(): array
    {
        return [new ProductDescriptor(
            'order-coupon-grid-filter',
            'Stoneholl Order Coupon Grid Filter',
            ['Stoneholl_OrderCouponGridFilter'],
            ['stoneholl/module-order-coupon-grid-filter']
        )];
    }
}
