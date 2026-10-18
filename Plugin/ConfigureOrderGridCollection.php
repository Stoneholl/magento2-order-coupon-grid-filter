<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Plugin;

use Magento\Framework\Data\Collection as DataCollection;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;
use Stoneholl\OrderCouponGridFilter\Model\ConfigProvider;
use Stoneholl\OrderCouponGridFilter\Model\ResourceModel\Order\Grid\Collection as OrderGridCollection;

class ConfigureOrderGridCollection
{
    private const ORDER_GRID_DATA_SOURCE = 'sales_order_grid_data_source';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * Initialize the order grid collection plugin.
     *
     * @param ConfigProvider $configProvider
     */
    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    /**
     * Configure only the specialized Admin order grid collection.
     *
     * @param CollectionFactory $subject
     * @param DataCollection $result
     * @param string $requestName
     * @return DataCollection
     */
    public function afterGetReport(
        CollectionFactory $subject,
        DataCollection $result,
        $requestName
    ): DataCollection {
        if ($requestName !== self::ORDER_GRID_DATA_SOURCE || !$result instanceof OrderGridCollection) {
            return $result;
        }

        $result->configureFeatures(
            $this->configProvider->isCouponCodeEnabled(),
            $this->configProvider->isCouponUsedEnabled(),
            $this->configProvider->isDiscountAmountEnabled()
        );

        return $result;
    }
}
