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
 * Class Price
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Product
 */
class DeleteItems extends \Magento\Backend\App\Action
{
    const CHUNK_SIZE = 1;

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
     * @var \Ced\Betterthat\Helper\Config
     */
    public $config;

    public $session;

    public $registry;

    public $resultJsonFactory;

    public $resultPageFactory;

    /**
     * Price constructor.
     *
     * @param \Magento\Backend\App\Action\Context              $context
     * @param \Magento\Framework\View\Result\PageFactory       $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter          $filter
     * @param \Magento\Catalog\Model\Product                   $collection
     * @param \Ced\Betterthat\Helper\Product                       $product
     * @param \Ced\Betterthat\Helper\Config                        $config
     * @param \Magento\Framework\Registry                      $registry
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\Product $collection,
        \Magento\Backend\App\Action\Context $context,
        \Ced\Betterthat\Helper\Product $product,
        \Ced\Betterthat\Helper\Config $config,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->filter               = $filter;
        $this->config               = $config;
        $this->registry             = $registry;
        $this->resultJsonFactory    = $resultJsonFactory;
        $this->catalogCollection    = $collection;
        $this->Betterthat              = $product;
        $this->resultPageFactory    = $resultPageFactory;
        $this->session              =  $context->getSession();
    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collectionFactory = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')->create();
        $collection = $this->filter->getCollection($collectionFactory);
        $deletedIds = $this->Betterthat->deleteProducts($collection->getAllIds());
        foreach ($collection as $product){
            if(in_array($product->getId(),$deletedIds)){
                $product->setbetterthat_product_status('DELETED');
                $product->setbetterthat_product_id('');
                $product->setbetterthat_feed_errors('');
                $product->getResource()->saveAttribute($product,'betterthat_feed_errors');
                $product->getResource()->saveAttribute($product,'betterthat_product_id');
                $product->getResource()->saveAttribute($product,'betterthat_product_status');
            }
        }
        if(count($deletedIds)>0)
             $this->messageManager->addSuccessMessage(json_encode($deletedIds) . ' item(s) deleted successfully');
        else
            $this->messageManager->addErrorMessage('Something went wrong');

        return $this->_redirect('*/product/index');
    }
}
