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

namespace Betterthat\Betterthat\Block\Adminhtml\Order;

use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use \Magento\Sales\Helper\Admin;
use Magento\Framework\ObjectManagerInterface;
use Betterthat\Betterthat\Model\Orders;

class Ship extends AbstractOrder implements TabInterface
{
    /**
     * @var string
     */
    public $_template = 'order/ship.phtml';

    /**
     * @var ObjectManagerInterface
     */
    public $objectManager;

    /**
     * @var Orders
     */
    public $order;

    /**
     * Ship constructor.
     *
     * @param Context                $context
     * @param Registry               $registry
     * @param Admin                  $adminHelper
     * @param ObjectManagerInterface $objectManager
     * @param Orders                 $orders
     * @param array                  $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        ObjectManagerInterface $objectManager,
        Orders $orders,
        array $data = []
    ) {
        parent::__construct($context, $registry, $adminHelper, $data);
        $this->objectManager = $objectManager;
        $this->order = $orders;
    }

    /**
     * GetObjectManager
     *
     * @return ObjectManagerInterface
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }
    /**
     * GetHelper
     *
     * @param string $helper
     * @return mixed
     */
    public function getHelper($helper)
    {
        $helper = $this->objectManager->get("Betterthat\Betterthat\Helper" . $helper);
        return $helper;
    }

    /**
     * GetModel
     *
     * @return \Magento\Framework\DataObject
     */
    public function getModel()
    {
        $orderId = $this->getOrder()->getId();
        $BetterthatOrder = $this->getObjectManager()->get(\Betterthat\Betterthat\Model\Orders::class)
            ->getCollection()->addFieldToFilter('magento_order_id', $orderId)
            ->getFirstItem();
        return $BetterthatOrder;
    }

    /**
     * SetOrderResult
     *
     * @param array $resultdata
     */
    public function setOrderResult($resultdata)
    {
        return $this->_coreRegistry->register('current_Betterthat_order', $resultdata);
    }

    /**
     * GetTabLabel
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Betterthat Order');
    }

    /**
     * GetTabTitle
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Betterthat');
    }

    /**
     * CanShowTab
     *
     * @return bool
     */
    public function canShowTab()
    {
        $data = $this->getModel();
        if (!empty($data->getData())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * IsHidden
     *
     * @return bool
     */
    public function isHidden()
    {
        $data = $this->getModel();
        if (!empty($data->getData())) {
            return false;
        } else {
            return true;
        }
    }
}
