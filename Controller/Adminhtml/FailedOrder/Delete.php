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

namespace Ced\Betterthat\Controller\Adminhtml\FailedOrder;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Delete
 *
 * @package Ced\Betterthat\Controller\Adminhtml\FailedOrder
 */
class Delete extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Ced\Betterthat\Model\OrderFailed
     */
    public $orderFailed;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * Delete constructor.
     *
     * @param Action\Context                          $context
     * @param PageFactory                             $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Ced\Betterthat\Model\OrderFailed           $orderFailed
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Ced\Betterthat\Model\OrderFailed $orderFailed
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->filter = $filter;
        $this->orderFailed = $orderFailed;
    }

    public function execute()
    {
        $isFilter = $this->getRequest()->getParam('filters');
        if (isset($isFilter)) {
            $collection = $this->filter->getCollection($this->orderFailed->getCollection());
        } else {
            $id = $this->getRequest()->getParam('id');
            if (isset($id) and !empty($id)) {
                $collection = $this->orderFailed->getCollection()->addFieldToFilter('id', ['eq' => $id]);
            }
        }

        $feedStatus = false;
        if (isset($collection) and $collection->getSize() > 0) {
            $feedStatus = true;
            $collection->walk('delete');
        }

        if ($feedStatus) {
            $this->messageManager->addSuccessMessage('Failed orders deleted successfully.');
        } else {
            $this->messageManager->addErrorMessage('Failed orders delete failed.');
        }
        $this->_redirect('betterthat/failedorder');
    }
}
