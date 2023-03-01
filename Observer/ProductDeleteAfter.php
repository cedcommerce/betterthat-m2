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
 * @copyright Copyright Betterthat (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProductDeleteAfter implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Betterthat\Betterthat\Helper\Config
     */
    public $config;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Betterthat\Betterthat\Helper\Product $betterthat
     * @param \Betterthat\Betterthat\Helper\Config $config
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Betterthat\Betterthat\Helper\Product $betterthat,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        $this->_objectManager = $objectManager;
        $this->messageManager = $messageManager;
        $this->betterthat = $betterthat;
        $this->config = $config;
    }

    /**
     * Customer register event handler
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $_product = $observer->getProduct();
        if ($this->config->isValid() == "0" ||
            $this->config->isValid() == null || !$_product->getBetterthatProductId()) {
            return $observer;
        }
        $_product = $observer->getProduct();
        $deletedIds = $this->betterthat->deleteProducts([$_product->getId()]);
        $this->betterthat->deleteProduct(['product_id'=>$_product->getId(),'delete_status'=>true]);
        if (count($deletedIds)>0) {
            $this->messageManager
                ->addSuccessMessage(
                    json_encode($deletedIds) . ' item(s) deleted successfully from BetterThat'
                );
        } else {
            $this->messageManager->addErrorMessage('Something went wrong');
        }
    }
}
