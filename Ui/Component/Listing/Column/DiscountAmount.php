<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Ui\Component\Listing\Column;

use Magento\Sales\Ui\Component\Listing\Column\PurchasedPrice;
use Stoneholl\OrderCouponGridFilter\Model\ConfigProvider;
use Stoneholl\OrderCouponGridFilter\Ui\Component\FeatureAwareInterface;

class DiscountAmount extends PurchasedPrice implements FeatureAwareInterface
{
    /**
     * @inheritdoc
     */
    public function getFeature(): string
    {
        return ConfigProvider::FEATURE_DISCOUNT_AMOUNT;
    }
}
