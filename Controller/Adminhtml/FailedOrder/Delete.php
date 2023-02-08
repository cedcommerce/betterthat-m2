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

namespace Betterthat\Betterthat\Controller\Adminhtml\FailedOrder;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Delete extends Action
{
    /**
     * @var PageFactoriee
     */
    public $resultPageFactory;

    /**
     * @var \Betterthat\Betterthat\Model\OrderFailed
     */
    public $orderFailed;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Betterthat\Betterthat\Model\OrderFailed $orderFailed
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Betterthat\Betterthat\Model\OrderFailed $orderFailed
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->filter = $filter;
        $this->orderFailed = $orderFailed;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $isFilter = $this->getRequest()->getParam('filters');
        if (isset($isFilter)) {
            $collection = $this->filter
                ->getCollection($this->orderFailed->getCollection());
        } else {
            $id = $this->getRequest()->getParam('id');
            if (isset($id) && !empty($id)) {
                $collection = $this->orderFailed
                    ->getCollection()->addFieldToFilter('id', ['eq' => $id]);
            }
        }
        $feedStatus = false;
        if (isset($collection) && $collection->getSize() > 0) {
            $feedStatus = true;
            $collection->walk('delete');
        }

        if ($feedStatus) {
            $this->messageManager
                ->addSuccessMessage('Failed orders deleted successfully.');
        } else {
            $this->messageManager
                ->addErrorMessage('Failed orders delete failed.');
        }
        $resultRedirect = $this->resultFactory->create('redirect');
        $resultRedirect->setUrl('*/betterthat/failedorder');
    }
}
