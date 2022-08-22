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

namespace Betterthat\Betterthat\Controller\Adminhtml\Product;

/**
 * Class Upload
 *
 * @package Betterthat\Betterthat\Controller\Adminhtml\Product
 */
class Upload extends \Magento\Backend\App\Action
{

    public const CHUNK_SIZE = 1;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \Betterthat\Betterthat\Helper\Product
     */
    public $Betterthat;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    public $catalogCollection;

    /**
     * @var \Betterthat\Betterthat\Helper\Config
     */

    public $session;

    public $registry;

    public $resultJsonFactory;

    public $resultPageFactory;

    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Catalog\Model\Product $collection
     * @param \Betterthat\Betterthat\Helper\Product $product
     * @param \Betterthat\Betterthat\Helper\Config $config
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\Product $collection,
        \Betterthat\Betterthat\Helper\Product $product,
        \Betterthat\Betterthat\Helper\Config $config,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->catalogCollection = $collection;
        $this->Betterthat = $product;
        $this->session = $context->getSession();
        $this->registry = $registry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->redirect = $redirect;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {

        // case 2 ajax request for chunk processing
        $batchId = $this->getRequest()->getParam('batchid');
        if (isset($batchId)) {
            $resultJson = $this->resultJsonFactory->create();
            $productIds = $this->session->getBetterthatProducts();
            $response = $this->Betterthat->createProducts($productIds[$batchId]);
            if (isset($response['err_code']) && $response['err_code'] == 'validation_err') {
                return $resultJson->setData(
                    [
                        'errors' => count($productIds[$batchId])
                            . ' Product Ids: ' . json_encode($productIds[$batchId])
                            . " Upload Failed. Reason: " . $response['message'],
                        'messages' => $response['message'],
                    ]
                );
            } elseif (isset($productIds[$batchId]) && $response) {
                if (isset($response['message'])
                    && in_array(
                        $response['message'],
                        [
                            "product already exists",
                            "product already exists in cleanse section."
                        ]
                    )
                ) {
                    return $resultJson->setData(
                        [
                            'success' => count($productIds[$batchId])
                                . ' Product Ids: '
                                . json_encode($productIds[$batchId])
                                . ' ' . $response['message'],
                            'messages' => $response['message']
                        ]
                    );
                } elseif (isset($response['bt_visibility'])) {
                    return $resultJson->setData(
                        [
                            'success' => count($productIds[$batchId])
                                . ' Product Ids: '
                                . json_encode($productIds[$batchId])
                                . ' '
                                . $response['message'],
                            'messages' => $response['message']
                        ]
                    );
                } else {
                    return $resultJson->setData(
                        [
                            'success' => count($productIds[$batchId])
                                . ' Product Ids: '
                                . json_encode($productIds[$batchId])
                                . ' Product will be reviewed first and get approved soon',
                            'messages' => $response['message']
                        ]
                    );
                }
            } else {
                return $resultJson->setData(
                    [
                        'errors' => count($productIds[$batchId])
                            . ' Product Ids: '
                            . json_encode($productIds[$batchId])
                            . " Upload Failed. Reason: Invalid item ",
                        'messages' => isset($response['message']) ? $response['message'] : '',
                    ]
                );
            }
        }

        // case 3 normal uploading and chunk creating
        $collection = $this->filter
            ->getCollection($this->catalogCollection->getCollection());
        $productIds = $collection->getAllIds();

        if (count($productIds) == 0) {
            $this->messageManager
                ->addErrorMessage('No Product selected to upload.');
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setUrl($this->redirect->getRefererUrl());
            return $resultRedirect;
        }

        // case 3.1 normal uploading if current ids are less than chunk size.
        if (count($productIds) == self::CHUNK_SIZE) {
            $response = $this->Betterthat->createProducts($productIds);
            if (!isset($response['err_code']) && !isset($response['error_key'])) {
                if (isset($response['message'])
                    &&
                    in_array(
                        $response['message'],
                        ["product already exists", "product already exists in cleanse section."]
                    )
                ) {
                    $this->messageManager->addSuccessMessage($response['message']);
                } elseif (isset($response['bt_visibility'])) {
                    $this->messageManager->addNoticeMessage($response['message']);
                } else {
                    $this->messageManager->addSuccessMessage(
                        count($productIds)
                        . 'Product(s) will reviewed first and get approved soon'
                    );
                }
            } else {
                if (isset($response['fields'][0]) && $response['fields'][0] == 'visibility') {
                    $this->messageManager
                        ->addNoticeMessage(
                            "Item's visibility is not visible hence can't be uploaded,
                            please update the visibility and try again!"
                        );
                } else {
                    $message = 'Product(s) Upload Failed.';
                    $errors = $this->registry->registry('Betterthat_product_errors');
                    if (isset($errors)) {
                        $message = "Product(s) Upload Failed. \nErrors: "
                            . (string)json_encode($errors);
                    }
                    $this->messageManager->addError($message);
                }
            }
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setUrl($this->redirect->getRefererUrl());
            return $resultRedirect;
        }
        // case 3.2 normal uploading if current ids are more than chunk size.
        $productIds = array_chunk($productIds, self::CHUNK_SIZE);
        $this->registry->register('productids', count($productIds));
        $this->session->setBetterthatProducts($productIds);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Betterthat_Betterthat::Betterthat');
        $resultPage->getConfig()
            ->getTitle()
            ->prepend(__('Upload Products'));
        return $resultPage;
    }
}
