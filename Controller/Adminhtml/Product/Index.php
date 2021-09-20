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
namespace Ced\Betterthat\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Product list page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /**
         * @var \Magento\Backend\Model\View\Result\Page $resultPage
         */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ced_Betterthat::product');
        $resultPage->getConfig()->getTitle()->prepend(__('Betterthat Product Listing'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ced_Betterthat::Betterthat');
    }
}
