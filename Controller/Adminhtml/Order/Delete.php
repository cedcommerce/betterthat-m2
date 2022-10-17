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

namespace Betterthat\Betterthat\Controller\Adminhtml\Order;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \Betterthat\Betterthat\Model\Orders
     */
    public $orders;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Betterthat\Betterthat\Model\Orders $collection
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Betterthat\Betterthat\Model\Orders $collection
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->orders = $collection;
    }

    /**
     * Execute
     *
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
            if (isset($id) && !empty($id)) {
                $collection = $this->orders->getCollection()
                    ->addFieldToFilter('id', ['eq' => $id]);
            }
        }

        $response = false;
        $message = 'Order(s) deleted successfully.';
        if (isset($collection) && $collection->getSize() > 0) {
            $response = $collection->walk('delete');
        }

        if ($response) {
            $this->messageManager->addSuccessMessage($message);
        } else {
            $this->messageManager->addErrorMessage('Order(s) delete failed.');
        }
        $resultRedirect = $this->resultFactory->create('redirect');
        return $resultRedirect->setPath(
            'betterthat/order/index'
        );
    }
}
