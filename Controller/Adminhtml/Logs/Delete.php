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

namespace Betterthat\Betterthat\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Delete extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Betterthat\Betterthat\Model\Logs
     */
    public $logs;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    public $fileIo;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Filesystem\Io\File $fileIo
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Betterthat\Betterthat\Model\Logs $logs
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem\Io\File $fileIo,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Betterthat\Betterthat\Model\Logs $logs
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->fileIo = $fileIo;
        $this->filter = $filter;
        $this->logs = $logs;
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
            $collection = $this->filter->getCollection($this->logs->getCollection());
        } else {
            $id = $this->getRequest()->getParam('id');
            if (isset($id) && !empty($id)) {
                $collection = $this->logs->getCollection()->addFieldToFilter('id', ['eq' => $id]);
            }
        }

        $logsStatus = true;
        if (isset($collection) && $collection->getSize() > 0) {
            $logsStatus = true;
            $collection->walk('delete');
        }

        if ($logsStatus) {
            $this->messageManager->addSuccessMessage('Logs deleted successfully.');
        } else {
            $this->messageManager->addErrorMessage('Logs delete failed.');
        }
        $resultRedirect = $this->resultFactory->create('redirect');
        $resultRedirect->setUrl('*/betterthat/logs');
    }
}
