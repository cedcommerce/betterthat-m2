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

/**
 * Class View
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Product
 */
class ValidateSingle extends \Magento\Backend\App\Action
{
    public $registry;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \Ced\Betterthat\Helper\Product
     */
    public $Betterthat;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    public $catalogCollection;

    /**
     * Json Factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * ValidateSingle constructor.
     *
     * @param \Magento\Backend\App\Action\Context              $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Ui\Component\MassAction\Filter          $filter
     * @param \Magento\Framework\Registry                      $registry
     * @param \Magento\Catalog\Model\Product                   $collection
     * @param \Ced\Betterthat\Helper\Config                    $config
     * @param \Ced\Betterthat\Helper\Product                   $product
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product $collection,
        \Ced\Betterthat\Helper\Config $config,
        \Ced\Betterthat\Helper\Product $product,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->filter = $filter;
        $this->catalogCollection = $collection;
        $this->Betterthat = $product;
        $this->redirect = $redirect;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $response = $this->Betterthat->validateAllProducts([$id]);
        if ($response && !isset($response['bt_visibility'])) {
            $this->messageManager
                ->addSuccessMessage(' Product(s) Validation Process Executed successfully.');
        } else {
            $message = 'Product Validate Failed.';
            if (isset($response['bt_visibility'])) {
                $message = $response['bt_visibility'];
            }
            $errors = $this->registry->registry('Betterthat_product_errors');
            if (isset($errors)) {
                $message = "Product Validate Failed. \nErrors: " . (string)json_encode($errors);
            }
            $this->messageManager->addError($message);
        }

        $resultRedirect = $this->resultFactory->create('redirect');
        $resultRedirect->setUrl($this->redirect->getRefererUrl());
        return $resultRedirect;
    }
}
