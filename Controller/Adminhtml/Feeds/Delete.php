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

namespace Ced\Betterthat\Controller\Adminhtml\Feeds;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Delete
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Feeds
 */
class Delete extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Ced\Betterthat\Model\Feeds
     */
    public $BetterthatFeeds;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    public $fileIo;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * Delete constructor.
     *
     * @param Action\Context                        $context
     * @param PageFactory                           $resultPageFactory
     * @param \Magento\Framework\Filesystem\Io\File $fileIo
     * @param \Ced\Betterthat\Model\Feeds               $BetterthatFeeds
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem\Io\File $fileIo,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Ced\Betterthat\Model\Feeds $BetterthatFeeds
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->fileIo = $fileIo;
        $this->filter = $filter;
        $this->BetterthatFeeds = $BetterthatFeeds;
    }

    public function execute()
    {
        $isFilter = $this->getRequest()->getParam('filters');
        if (isset($isFilter)) {
            $collection = $this->filter->getCollection($this->BetterthatFeeds->getCollection());
        } else {
            $id = $this->getRequest()->getParam('id');
            if (isset($id) and !empty($id)) {
                $collection = $this->BetterthatFeeds->getCollection()->addFieldToFilter('id', ['eq' => $id]);
            }
        }

        $feedStatus = false;
        if (isset($collection) and $collection->getSize() > 0) {
            $feedStatus = true;
            foreach ($collection as $feed) {
                $feedFile = $feed->getFeedFile();
                if ($this->fileIo->fileExists($feedFile)) {
                    $this->fileIo->rm($feedFile);
                }

                $responseFile = $feed->getResponseFile();
                if ($this->fileIo->fileExists($responseFile)) {
                    $this->fileIo->rm($responseFile);
                }

                $feedStatus = $feed->delete();
            }
        }

        if ($feedStatus) {
            $this->messageManager->addSuccessMessage('Feed deleted successfully.');
        } else {
            $this->messageManager->addErrorMessage('Feed delete failed.');
        }
        $this->_redirect('Betterthat/feeds');
    }
}
