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

namespace Betterthat\Betterthat\Cron\Feed;

class Sync
{
    public $logger;
    public $feeds;
    public $product;
    public $systemLogger;

    /**
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     */
    public function __construct(
        \Betterthat\Betterthat\Model\Feeds $BetterthatFeeds,
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Betterthat\Betterthat\Helper\Product $product,
        \Psr\Log\LoggerInterface $systemLogger,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        $this->feeds = $BetterthatFeeds;
        $this->logger = $logger;
        $this->product = $product;
        $this->systemLogger = $systemLogger;
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        try {
            $invCron = $this->config->getFeedSyncCron();
            if ($invCron == '1') {
                $this->logger->info('Feed Sync Cron Enable', ['path' => __METHOD__, 'Cron Status' => 'Enable']);
                $feedIds = $this->feeds->getCollection()
                    ->addFieldToFilter('status', ['neq' => 'COMPLETE'])
                    ->setPageSize(20)
                    ->setCurPage(1)
                    ->setOrder('id', 'DESC');
                foreach ($feedIds as $feed) {
                    $response = $this->product->syncFeeds($feed);
                }
                return $response;
            } else {
                $this->logger->info('Feed Sync Cron Disabled', ['path' => __METHOD__, 'Cron Status' => 'Disable']);
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->error(
                'Feed Sync Cron',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
    }
}
