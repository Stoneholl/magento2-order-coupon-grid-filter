<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Model\ResourceModel\Order\Grid;

use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;

class Collection extends SalesOrderGridCollection
{
    private const ORDER_TABLE_ALIAS = 'stoneholl_order_coupon';
    private const COUPON_USED_FIELD = 'coupon_used';

    /**
     * @var bool
     */
    private $couponCodeEnabled = false;

    /**
     * @var bool
     */
    private $couponUsedEnabled = false;

    /**
     * @var bool
     */
    private $featuresConfigured = false;

    /**
     * Add only the order data required by enabled features.
     *
     * @param bool $couponCodeEnabled
     * @param bool $couponUsedEnabled
     * @param bool $discountAmountEnabled
     * @return $this
     */
    public function configureFeatures(
        bool $couponCodeEnabled,
        bool $couponUsedEnabled,
        bool $discountAmountEnabled
    ) {
        if ($this->featuresConfigured) {
            return $this;
        }

        $this->featuresConfigured = true;
        $this->couponCodeEnabled = $couponCodeEnabled;
        $this->couponUsedEnabled = $couponUsedEnabled;

        if (!$couponCodeEnabled && !$couponUsedEnabled && !$discountAmountEnabled) {
            return $this;
        }

        $columns = [];
        if ($couponCodeEnabled) {
            $columns['coupon_code'] = 'coupon_code';
        }
        if ($discountAmountEnabled) {
            $columns['discount_amount'] = 'discount_amount';
        }

        $orderTableAlias = self::ORDER_TABLE_ALIAS;

        $this->getSelect()->joinLeft(
            [$orderTableAlias => $this->getTable('sales_order')],
            $orderTableAlias . '.entity_id = main_table.entity_id',
            $columns
        );

        if ($couponCodeEnabled) {
            $this->addFilterToMap('coupon_code', $orderTableAlias . '.coupon_code');
        }
        if ($discountAmountEnabled) {
            $this->addFilterToMap('discount_amount', $orderTableAlias . '.discount_amount');
        }

        return $this;
    }

    /**
     * Apply the virtual Coupon Used filter to the joined coupon code.
     *
     * @param string|array $field
     * @param null|string|array $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'coupon_code' && !$this->couponCodeEnabled) {
            return $this;
        }

        if ($field !== self::COUPON_USED_FIELD) {
            return parent::addFieldToFilter($field, $condition);
        }

        if (!$this->couponUsedEnabled) {
            return $this;
        }

        $value = $this->extractFilterValue($condition);
        $couponCodeField = $this->getConnection()->quoteIdentifier(
            self::ORDER_TABLE_ALIAS . '.coupon_code'
        );

        if ($value === '1') {
            $this->getSelect()->where(
                sprintf('(%1$s IS NOT NULL AND %1$s <> ?)', $couponCodeField),
                ''
            );
        } elseif ($value === '0') {
            $this->getSelect()->where(
                sprintf('(%1$s IS NULL OR %1$s = ?)', $couponCodeField),
                ''
            );
        } else {
            $this->getSelect()->where('1 = 0');
        }

        return $this;
    }

    /**
     * Normalize a filter condition to its scalar value.
     *
     * @param mixed $condition
     * @return string
     */
    private function extractFilterValue($condition): string
    {
        if (is_array($condition)) {
            if (array_key_exists('eq', $condition)) {
                $condition = $condition['eq'];
            } elseif (count($condition) === 1) {
                $condition = reset($condition);
            }
        }

        return is_scalar($condition) ? (string) $condition : '';
    }
}
