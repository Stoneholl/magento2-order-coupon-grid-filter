<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CouponUsed implements OptionSourceInterface
{
    public const YES = 1;
    public const NO = 0;

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => self::YES, 'label' => __('Yes')],
            ['value' => self::NO, 'label' => __('No')],
        ];
    }
}
