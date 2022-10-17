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

class Reject extends \Magento\Backend\App\Action
{
    public const CHUNK_SIZE = 10;
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Betterthat_Betterthat::Betterthat_orders';
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    public $resultRedirectFactory;
    /**
     * @var \Betterthat\Betterthat\Helper\Order
     */
    public $orderHelper;

    /**
     * @var \Betterthat\Betterthat\Helper\Product
     */
    public $Betterthat;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Betterthat\Betterthat\Helper\Order $orderHelper
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Betterthat\Betterthat\Model\OrderFailed $collection
     * @param \Betterthat\Betterthat\Helper\Product $product
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Betterthat\Betterthat\Helper\Order $orderHelper,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Betterthat\Betterthat\Model\OrderFailed $collection,
        \Betterthat\Betterthat\Helper\Product $product,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->orderHelper = $orderHelper;
        $this->filter = $filter;
        $this->orders = $collection;
        $this->Betterthat = $product;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session =  $context->getSession();
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->redirect = $redirect;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        if (!$this->Betterthat->checkForConfiguration()) {
            $this->messageManager->addErrorMessage(
                __('Products Upload Failed. Betterthat API not
                enabled or Invalid. Please check Betterthat Configuration.')
            );
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setUrl($this->redirect->getRefererUrl());
            return $resultRedirect;
        }

        // case 2 ajax request for chunk processing
        $batchId = $this->getRequest()->getParam('batchid');
        if (isset($batchId)) {
            $resultJson = $this->resultJsonFactory->create();
            $orderIds = $this->session->getBetterthatOrders();
            $response = $this->orderHelper->rejectOrCancelOrder($orderIds[$batchId]);
            if (isset($orderIds[$batchId]) && $response) {
                return $resultJson->setData(
                    [
                        'success' => count($orderIds[$batchId]) . "Order Rejected Successfully",
                        'messages' => $response//$this->registry->registry('Betterthat_product_errors')
                    ]
                );
            }
            return $resultJson->setData(
                [
                    'error' => count($orderIds[$batchId]) . "Order Rejection Failed",
                    'messages' => $this->registry->registry('Betterthat_order_errors'),
                ]
            );
        }

        // case 3 normal uploading and chunk creating
        $collection = $this->filter->getCollection($this->orders->getCollection());
        $orderIds = $collection->getColumnValues('Betterthat_order_id');

        if (count($orderIds) == 0) {
            $this->messageManager->addErrorMessage('No Order selected to rejected.');
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setUrl($this->redirect->getRefererUrl());
            return $resultRedirect;
        }

        // case 3.1 normal uploading if current ids are less than chunk size.
        if (count($orderIds) < self::CHUNK_SIZE) {
            $response = $this->orderHelper->rejectOrCancelOrder($orderIds);
            if ($response) {
                $this->messageManager->addSuccessMessage(count($orderIds) . ' Order(s) Rejected Successfully');
            } else {
                $message = 'Order(s) Rejection Failed.';
                $errors = $this->registry->registry('Betterthat_order_errors');
                if (isset($errors)) {
                    $message = "Order(s) Rejection Failed. \nErrors: " . (string)json_encode($errors);
                }
                $this->messageManager->addError($message);
            }
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setUrl($this->redirect->getRefererUrl());
            return $resultRedirect;
        }
        // case 3.2 normal uploading if current ids are more than chunk size.
        $orderIds = array_chunk($orderIds, self::CHUNK_SIZE);
        $this->registry->register('orderids', count($orderIds));
        $this->session->setBetterthatOrders($orderIds);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Betterthat_Betterthat::Betterthat');
        $resultPage->getConfig()->getTitle()->prepend(__('Reject Orders'));
        return $resultPage;
    }
}
