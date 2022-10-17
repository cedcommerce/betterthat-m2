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

class Import
{
    /**
     * @var \Betterthat\Betterthat\Helper\Logger
     */
    public $logger;

    /**
     * @param \Betterthat\Betterthat\Helper\Order $order
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     * @param \Betterthat\Betterthat\Helper\Config $config
     */
    public function __construct(
        \Betterthat\Betterthat\Helper\Order $order,
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        $this->order = $order;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Execute
     *
     * @return bool
     */
    public function execute()
    {
        try {
            $orderCron = $this->config->getOrderCron();
            if ($orderCron == '1') {
                $this->logger->info('Order Fetch Cron Enable', ['path' => __METHOD__, 'Cron Status' => 'Enable']);
                $order = $this->order->importOrders();
                $this->logger->info(
                    'Order Fetch Cron Response',
                    ['path' => __METHOD__, 'OrderFetchReponse' => var_export($order)
                    ]
                );
                return $order;
            } else {
                $this->logger->info(
                    'Order Fetch Cron Disabled',
                    ['path' => __METHOD__, 'Cron Status' => 'Disable']
                );
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->error(
                'Order Import Cron',
                ['path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
    }
}
