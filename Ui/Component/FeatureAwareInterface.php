<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Ui\Component;

use Magento\Framework\View\Element\UiComponentInterface;

interface FeatureAwareInterface extends UiComponentInterface
{
    /**
     * Return the configuration feature represented by the component.
     *
     * @return string
     */
    public function getFeature(): string;
}
