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

class Truncate extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Betterthat\Betterthat\Model\Logs
     */
    public $feeds;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    public $fileIo;
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    public $redirect;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Filesystem\Io\File $fileIo
     * @param \Betterthat\Betterthat\Model\Logs $BetterthatLogs
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem\Io\File $fileIo,
        \Betterthat\Betterthat\Model\Logs $BetterthatLogs,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->fileIo = $fileIo;
        $this->feeds = $BetterthatLogs;
        $this->redirect = $redirect;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $collection = $this->feeds->getCollection();
        $collection->walk('delete');
        $this->messageManager->addSuccessMessage('Logs deleted successfully.');
        $resultRedirect = $this->resultFactory->create('redirect');
        return $resultRedirect->setPath(
            '*/*'
        );
    }
}
