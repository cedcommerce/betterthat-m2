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

namespace Ced\Betterthat\Controller\Adminhtml\Order;

/**
 * Class Delete
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Order
 */
class Download extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \Ced\Betterthat\Model\Orders
     */
    public $orders;

    /**
     * @var \Ced\Betterthat\Helper\Order
     */
    public $orderHelper;

    /**
     * Delete constructor.
     *
     * @param \Magento\Backend\App\Action\Context     $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Ced\Betterthat\Model\Orders                $collection
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Ced\Betterthat\Model\Orders $collection,
        \Ced\Betterthat\Helper\Order $orderHelper
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->orders = $collection;
        $this->orderHelper = $orderHelper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $isFilter = $this->getRequest()->getParam('filters');
        if (isset($isFilter)) {
            $collection = $this->filter->getCollection($this->orders->getCollection());
        } else {
            $id = $this->getRequest()->getParam('id');
            if (isset($id) and !empty($id)) {
                $collection = $this->orders->getCollection()->addFieldToFilter('id', ['eq' => $id]);
            }
        }

        $response = false;
        if (isset($collection) and $collection->getSize() > 0) {
            $response = $this->orderHelper->downloadOrderDocument($collection->getFirstItem()->getBetterthatOrderId());
        }

        if ($response) {
            //$this->messageManager->addSuccessMessage('Order Document Downloaded successfully.');
        } else {
            $this->messageManager->addErrorMessage('Order Document Download failed.');
        }

        return $this->_redirect('Betterthat/order/index');
    }
}
