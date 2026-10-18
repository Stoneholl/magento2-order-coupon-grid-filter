<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider
{
    public const FEATURE_COUPON_CODE = 'coupon_code';
    public const FEATURE_COUPON_USED = 'coupon_used';
    public const FEATURE_DISCOUNT_AMOUNT = 'discount_amount';

    private const XML_PATH_FEATURES = 'stoneholl_order_coupon_grid_filter/features/';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Initialize the configuration provider.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check whether the Coupon Code column and filter are enabled.
     *
     * @return bool
     */
    public function isCouponCodeEnabled(): bool
    {
        return $this->isFeatureEnabled(self::FEATURE_COUPON_CODE);
    }

    /**
     * Check whether the Coupon Used filter is enabled.
     *
     * @return bool
     */
    public function isCouponUsedEnabled(): bool
    {
        return $this->isFeatureEnabled(self::FEATURE_COUPON_USED);
    }

    /**
     * Check whether the Discount Amount column is enabled.
     *
     * @return bool
     */
    public function isDiscountAmountEnabled(): bool
    {
        return $this->isFeatureEnabled(self::FEATURE_DISCOUNT_AMOUNT);
    }

    /**
     * Check whether a supported feature is enabled.
     *
     * @param string $feature
     * @return bool
     */
    public function isFeatureEnabled(string $feature): bool
    {
        if (!in_array($feature, $this->getFeatures(), true)) {
            return false;
        }

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FEATURES . $feature,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }

    /**
     * Return supported feature identifiers.
     *
     * @return string[]
     */
    private function getFeatures(): array
    {
        return [
            self::FEATURE_COUPON_CODE,
            self::FEATURE_COUPON_USED,
            self::FEATURE_DISCOUNT_AMOUNT,
        ];
    }
}
