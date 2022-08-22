<?php
/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright Betterthat (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Controller\Adminhtml\Profile;

/**
 * Class Save
 *
 * @package Betterthat\Betterthat\Controller\Adminhtml\Profile
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
     * @var \Betterthat\Betterthat\Model\ProfileProductFactory
     */
    public $profileProduct;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    public $moduleDataSetup;

    /**
     * @var \Betterthat\Betterthat\Model\ProfileFactory
     */
    public $profileFactory;

    /**
     * @var \Betterthat\Betterthat\Helper\Profile
     */
    public $profileHelper;

    public $resultJsonFactory;

    /**
     * Validate constructor.
     *
     * @param \Magento\Backend\App\Action\Context                                 $context
     * @param \Magento\Framework\Registry                                         $registory
     * @param \Magento\Config\Model\Config\Structure                              $configStructure
     * @param \Magento\Config\Model\Config\Factory                                $configFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory     $categoryCollection
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory      $catalogCollection
     * @param \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configurable
     * @param \Magento\Framework\View\Result\PageFactory                          $resultPageFactory
     * @param \Betterthat\Betterthat\Model\ProfileProductFactory                         $profileProduct
     * @param \Betterthat\Betterthat\Model\ProfileFactory                                $profileFactory
     * @param \Betterthat\Betterthat\Helper\Profile                                      $profileHelper
     * @param \Magento\Framework\Controller\Result\JsonFactory                    $jsonFactory
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
        \Betterthat\Betterthat\Model\ProfileProductFactory $profileProduct,
        \Betterthat\Betterthat\Model\ProfileFactory $profileFactory,
        \Betterthat\Betterthat\Helper\Profile $profileHelper,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
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
