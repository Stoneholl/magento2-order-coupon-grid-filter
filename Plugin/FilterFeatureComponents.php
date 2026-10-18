<?php
declare(strict_types=1);

namespace Stoneholl\OrderCouponGridFilter\Plugin;

use Magento\Framework\View\Element\UiComponentInterface;
use Stoneholl\OrderCouponGridFilter\Model\ConfigProvider;
use Stoneholl\OrderCouponGridFilter\Ui\Component\FeatureAwareInterface;

class FilterFeatureComponents
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * Initialize feature filtering for the order grid component tree.
     *
     * @param ConfigProvider $configProvider
     */
    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    /**
     * Exclude disabled children from preparation, rendering and export traversal.
     *
     * A JavaScript componentDisabled flag alone leaves PHP formatters and export
     * option readers active. Filter the server-side children before those run.
     *
     * @param UiComponentInterface $subject
     * @param UiComponentInterface[] $result
     * @return UiComponentInterface[]
     */
    public function afterGetChildComponents(UiComponentInterface $subject, array $result): array
    {
        if ($subject->getContext()->getNamespace() !== 'sales_order_grid') {
            return $result;
        }

        foreach ($result as $name => $component) {
            if ($component instanceof FeatureAwareInterface
                && !$this->configProvider->isFeatureEnabled($component->getFeature())
            ) {
                unset($result[$name]);
            }
        }

        return $result;
    }
}
