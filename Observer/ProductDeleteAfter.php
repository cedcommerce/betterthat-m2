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
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProductDeleteAfter implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Ced\Betterthat\Helper\Product $betterthat
    ) {
        $this->_objectManager = $objectManager;
        $this->messageManager = $messageManager;
        $this->betterthat = $betterthat;
    }

    /**
     * customer register event handler
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
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
