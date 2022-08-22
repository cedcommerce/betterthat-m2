<?php

/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Cron\Order;

class Shipment
{
    public $logger;

    /**
     * @var $config
     */
    public $config;

    /**
     * Import constructor.
     *
     * @param \Betterthat\Betterthat\Helper\Order  $order
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     */
    public function __construct(
        \Betterthat\Betterthat\Helper\Order $order,
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Betterthat\Betterthat\Model\Orders $collection,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        $this->order = $order;
        $this->logger = $logger;
        $this->orders = $collection;
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        try {
            $orderSyncCron = $this->config->getOrderShipmentCron();
            if ($orderSyncCron == '1') {
                $orderCollection = $this->orders->getCollection()
                    ->addFieldToFilter('status', ['in', ['SHIPPING']]);
                $orderIds = $orderCollection->getColumnValues('Betterthat_order_id');
                $syncResponse = $this->order->shipOrders($orderCollection);
                $this->logger->info(
                    'Shipment Order Cron Response',
                    [
                        'path' => __METHOD__,
                        'OrderIds' => implode(',', $orderIds),
                        'OrderShipmentReponse' => var_export($syncResponse)
                    ]
                );
                return $syncResponse;
            } else {
                $this->logger->info(
                    'Shipment Cron Disabled',
                    ['path' => __METHOD__,
                        'Cron Status' => 'Disable']
                );
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->error(
                'Order Shipment Cron',
                [
                'path' => __METHOD__,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()]
            );
        }
    }
}
