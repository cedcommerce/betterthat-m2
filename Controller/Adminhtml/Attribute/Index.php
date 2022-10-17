<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 12/1/18
 * Time: 3:51 PM
 */

namespace Betterthat\Betterthat\Controller\Adminhtml\Attribute;

class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /**
         * @var \Magento\Backend\Model\View\Result\Page $resultPage
        */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Betterthat_Betterthat::Betterthat');
        $resultPage->getConfig()->getTitle()->prepend(__('Betterthat Category Attributes'));
        return $resultPage;
    }

    /**
     * IsAllowed
     *
     * @return mixed
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Betterthat_Betterthat::Betterthat');
    }
}
