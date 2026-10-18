<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Ui\Component\Filter;

use Magento\Ui\Component\Filters\Type\Select;
use Stoneholl\OrderCouponGridFilter\Model\ConfigProvider;
use Stoneholl\OrderCouponGridFilter\Ui\Component\FeatureAwareInterface;

class CouponUsed extends Select implements FeatureAwareInterface
{
    /**
     * @inheritdoc
     */
    public function getFeature(): string
    {
        return ConfigProvider::FEATURE_COUPON_USED;
    }
}
