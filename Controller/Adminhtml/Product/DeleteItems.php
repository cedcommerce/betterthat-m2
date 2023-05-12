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

class DeleteItems extends \Magento\Backend\App\Action
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
    public $config;
    /**
     * @var \Magento\Backend\Model\Session
     */
    public $session;
    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;
    /**
     * @var \Magento\Backend\Model\View\Result\Redirect
     */
    public $resultRedirect;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Catalog\Model\Product $collection
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Betterthat\Betterthat\Helper\Product $product
     * @param \Betterthat\Betterthat\Helper\Config $config
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Backend\Model\View\Result\Redirect $redirect
     */
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\Product $collection,
        \Magento\Backend\App\Action\Context $context,
        \Betterthat\Betterthat\Helper\Product $product,
        \Betterthat\Betterthat\Helper\Config $config,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Backend\Model\View\Result\Redirect $redirect
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
        $this->resultRedirect = $redirect;
    }

    /**
     * Execute
     *
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collectionFactory = $this->_objectManager
            ->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class)
            ->create();
        $collection = $this->filter->getCollection($collectionFactory);
        $collection->addFieldToFilter('betterthat_product_status', ['eq' => 'UPLOADED']);
        $deletedIds = $this->Betterthat->deleteProducts($collection->getAllIds());
        foreach ($collection as $product) {
            if (in_array($product->getId(), $deletedIds)) {
                $product->setbetterthat_product_status('DELETED');
                $product->setbetterthat_visibility('no');
                $product->setbetterthat_product_id('');
                $product->setbetterthat_feed_errors('');
                $product->getResource()->saveAttribute($product, 'betterthat_feed_errors');
                $product->getResource()->saveAttribute($product, 'betterthat_product_id');
                $product->getResource()->saveAttribute($product, 'betterthat_product_status');
            }
        }
        if (count($deletedIds)>0) {
             $this->messageManager
                 ->addSuccessMessage(json_encode($deletedIds) . ' item(s) deleted successfully');
        } else {
            $this->messageManager
                ->addErrorMessage('Only Uploaded items can be deleted!');
        }
        return $this->resultRedirect->setPath('*/product/index');
    }
}
