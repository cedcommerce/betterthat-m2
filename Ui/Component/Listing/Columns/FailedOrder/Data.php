<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Ui\Component\Listing\Columns\FailedOrder;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Data
 */
class Data extends Column
{

    /**
     * Data constructor.
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        $components = [],
        $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $order_response = $item[$name];
                $item[$name] = [];
                if (isset($item['id'])) {
                    $orderId = $item['Betterthat_order_id'];
                    $item[$name]['view'] = [
                        'label' => __('View'),
                        'class' => 'cedcommerce actions view',
                        'popup' => [
                            'title' => __("Order Info #{$orderId}"),
                            'message' => $order_response,
                            'type' => 'json',
                            'render' => 'html',
                        ],
                    ];
                }
            }
        }
        return $dataSource;
    }
}
