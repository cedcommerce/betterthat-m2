<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Betterthat
 * @author        CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license      https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Controller\Adminhtml\Profile;

/**
 * Class Save
 * @package Ced\Betterthat\Controller\Adminhtml\Profile
 */
class Validate extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    public $registory;

    /**
     * @var CollectionFactory
     */
    public $catalogCollection;


    public $categoryCollection;

    /**
     * @var \Ced\Betterthat\Model\ProfileProductFactory
     */
    public $profileProduct;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    public $moduleDataSetup;

    /**
     * @var \Ced\Betterthat\Model\ProfileFactory
     */
    public $profileFactory;

    /**
     * @var \Ced\Betterthat\Helper\Profile
     */
    public $profileHelper;

    public $resultJsonFactory;

    /**
     * Validate constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registory
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param \Magento\Config\Model\Config\Factory $configFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $catalogCollection
     * @param \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configurable
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Ced\Betterthat\Model\ProfileProductFactory $profileProduct
     * @param \Ced\Betterthat\Model\ProfileFactory $profileFactory
     * @param \Ced\Betterthat\Helper\Profile $profileHelper
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registory,
        \Magento\Config\Model\Config\Structure $configStructure,
        \Magento\Config\Model\Config\Factory $configFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $catalogCollection,
        \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configurable,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Ced\Betterthat\Model\ProfileProductFactory $profileProduct,
        \Ced\Betterthat\Model\ProfileFactory $profileFactory,
        \Ced\Betterthat\Helper\Profile $profileHelper,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->configStructure = $configStructure;
        $this->registory = $registory;
        $this->configFactory = $configFactory;
        $this->productConfigFactory = $configurable;
        $this->catalogCollection = $catalogCollection;
        $this->categoryCollection = $categoryCollection;
        $this->profileHelper = $profileHelper;
        $this->profileFactory = $profileFactory;
        $this->profileProduct = $profileProduct;
        $this->resultJsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $response = new \Magento\Framework\DataObject();
        $response->setError(0);
        $resultJson = $this->resultJsonFactory->create();
        if ($response->getError()) {
            $response->setError(true);
            $response->setMessages($response->getMessages());
        }
        $resultJson->setData($response);
        return $resultJson;
    }

}
