<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Stoneholl\OrderCouponGridFilter\Model\ConfigProvider;
use Stoneholl\OrderCouponGridFilter\Ui\Component\FeatureAwareInterface;

class CouponCode extends Column implements FeatureAwareInterface
{
    /**
     * @inheritdoc
     */
    public function getFeature(): string
    {
        return ConfigProvider::FEATURE_COUPON_CODE;
    }
}
