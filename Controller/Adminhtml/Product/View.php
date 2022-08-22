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
 * Class View
 *
 * @package Betterthat\Betterthat\Controller\Adminhtml\Product
 */
class View extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \BetterthatSdk\ProductFactory
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
     * View constructor.
     *
     * @param \Magento\Backend\App\Action\Context              $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Ui\Component\MassAction\Filter          $filter
     * @param \Magento\Catalog\Model\Product                   $collection
     * @param \Betterthat\Betterthat\Helper\Config                    $config
     * @param \BetterthatSdk\ProductFactory                    $Betterthat
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\Product $collection,
        \Betterthat\Betterthat\Helper\Config $config,
        \BetterthatSdk\ProductFactory $Betterthat
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->filter = $filter;
        $this->catalogCollection = $collection;
        $this->Betterthat = $Betterthat;
        $this->config = $config;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $sku = $this->getRequest()->getParam('sku');
        $products = $this->Betterthat->create(['config' => $this->config->getApiConfig()])
            ->getProducts(['SkuSellerList' => json_encode([$sku])]);
        return $this->resultJsonFactory
            ->create()
            ->setData($products->getBody());
    }
}
