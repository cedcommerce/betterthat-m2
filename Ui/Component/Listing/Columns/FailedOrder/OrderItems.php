<?php
/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Ui\Component\Listing\Columns\FailedOrder;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class OrderItems extends Column
{
    /**
     * PrepareDataSource
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $response = $item[$name];
                $item[$name] = [];
                if (isset($item['id'])) {
                    $orderId = $item['Betterthat_order_id'];
                    $item[$name]['view'] = [
                        'label' => __('View'),
                        'class' => 'betterthat actions view',
                        'popup' => [
                            'title' => __("Order Data #{$orderId}"),
                            'message' => $response,
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
