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

namespace Betterthat\Betterthat\Observer;

use Magento\Framework\Event\ObserverInterface;

class SalesModelOrderObserver implements ObserverInterface
{

    /**
     * @var \Betterthat\Betterthat\Helper\Logger
     */
    public $logger;

    /**
     * Object Managerr
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Betterthat\Betterthat\Helper\Logger $logger
    ) {
        $this->objectManager = $objectManager;
        $this->logger = $logger;
    }

    /**
     * Catalog product save after event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $this->product = $this->objectManager->create(\Magento\Catalog\Model\ProductFactory::class);
            $this->productHelper = $this->objectManager->create(\Betterthat\Betterthat\Helper\Product::class);

            $this->logger
                ->addInfo('Sales Order Observer: success', ['path' => __METHOD__, 'Response' => 'enter']);
            $event = $observer->getEvent();
            $order = $event->getOrder();
            foreach ($order->getAllItems() as $item) {
                $productId = $item->getProductId();
                $product = $this->product->create()->load($productId);
                if ($product->getBetterthatProfileId()) {
                    $this->productHelper->updatePriceInventory([$productId]);
                }
            }
            $this->logger->addInfo('Sales Order Observer: success', ['path' => __METHOD__, 'Response' => 'exit']);
        } catch (\Exception $e) {
            $this->logger
                ->addError('Sales Order Observer: success', ['path' => __METHOD__, 'exception' => $e->getMessage()]);
            return $observer;
        }
        return $observer;
    }
}
