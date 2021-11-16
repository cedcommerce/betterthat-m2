<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Cron\Order;

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
     * @param \Ced\Betterthat\Helper\Order  $order
     * @param \Ced\Betterthat\Helper\Logger $logger
     */
    public function __construct(
        \Ced\Betterthat\Helper\Order $order,
        \Ced\Betterthat\Helper\Logger $logger,
        \Ced\Betterthat\Model\Orders $collection,
        \Ced\Betterthat\Helper\Config $config
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
                    ->addFieldToFilter('status', array('in', array('SHIPPING')));
                $orderIds = $orderCollection->getColumnValues('Betterthat_order_id');
                $syncResponse = $this->order->shipOrders($orderCollection);
                $this->logger->info('Shipment Order Cron Response', ['path' => __METHOD__, 'OrderIds' => implode(',', $orderIds), 'OrderShipmentReponse' => var_export($syncResponse)]);
                return $syncResponse;
            } else {
                $this->logger->info('Shipment Cron Disabled', ['path' => __METHOD__, 'Cron Status' => 'Disable']);
            }
            return false;
        } catch (\Exception $e){
            $this->logger->error('Order Shipment Cron', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }
}
