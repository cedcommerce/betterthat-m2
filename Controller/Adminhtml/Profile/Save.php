<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_BetterThat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\BetterThat\Controller\Adminhtml\Profile;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Magento\Framework\DataObject;

/**
 * Class Save
 *
 * @package Ced\BetterThat\Controller\Adminhtml\Profile
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    public $registory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public $catalogCollection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    public $categoryCollection;

    /**
     * @var \Ced\BetterThat\Model\ProfileProductFactory
     */
    public $profileProduct;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    public $moduleDataSetup;

    /**
     * @var \Ced\BetterThat\Model\ProfileFactory
     */
    public $profileFactory;

    /**
     * @var \Ced\BetterThat\Helper\Profile
     */
    public $profileHelper;

    /**
     * @var DataObject
     */
    public $data;
    public $configFactory;

    public $configStructure;

    public $productConfigFactory;

    public $resultPageFactory;

    public $logger;
    public $BetterThatCache;

    public $profileresource;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registory,
        \Magento\Config\Model\Config\Structure $configStructure,
        \Magento\Config\Model\Config\Factory $configFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $catalogCollection,
        \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configurable,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\DataObject $data,
        \Psr\Log\LoggerInterface $logger,
        \Ced\Betterthat\Model\ProfileProductFactory $profileProduct,
        \Ced\Betterthat\Model\ProfileFactory $profileFactory,
        \Ced\Betterthat\Helper\Cache $BetterThatCache,
        \Ced\Betterthat\Helper\Profile $profileHelper,
        \Ced\Betterthat\Model\ResourceModel\ProfileFactory $resourceModel
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
        $this->profileresource = $resourceModel;
        $this->profileProduct = $profileProduct;
        $this->BetterThatCache = $BetterThatCache;
        $this->data = $data;
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('Saving Started');
        $profileId = null;
        $returnToEdit = true;

        if ($this->validate()) {
            try {
                $profileModel = $this->profileFactory->create();
                $profile = $this->profileresource->create()->load($profileModel,$this->data->getProfileId(),'id');
                //$profile = $this->profileFactory->create()->load($this->data->getProfileId());

                $profileModel->addData($this->data->getData());

                $profile->save($profileModel);

                $profileModel->removeProducts($profileModel->getMagentoCategory());
                $profileModel->addProducts($profileModel->getMagentoCategory());
                $profileId = $profileModel->getId();
                if($profileId) {
                    $this->BetterThatCache->removeValue(\Ced\BetterThat\Helper\Cache::PROFILE_CACHE_KEY . $profileId);
                }
                $this->messageManager->addSuccessMessage(__('Profile save successfully.'));
            } catch (\Magento\Framework\Exception\AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage(__('Profile code already exists. '.$e->getMessage()));
            }
            catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            }

        }


        $resultRedirect = $this->resultRedirectFactory->create();
        if ($returnToEdit) {
            if ($profileId) {
                $resultRedirect->setPath(
                    'betterthat/profile/edit',
                    ['id' => $profileId, '_current' => true]
                );
            } else {
                $resultRedirect->setPath(
                    'betterthat/profile/edit',
                    ['_current' => true]
                );
            }
        } else {
            $resultRedirect->setPath('*/*/index');
        }
        $this->logger->info('Saving Ended');
        return $resultRedirect;
    }

    private function validate()
    {
        $generalInformation = $this->getRequest()->getParam('general_information');
        $offer_information = $this->getRequest()->getParam('offer_information');
        $BetterThat = $this->getRequest()->getParam('BetterThat');
        $store_categories = $this->getRequest()->getParam('betterthat_category');
        $BetterThatAttributes = $this->getRequest()->getParam('betterthat_attributes');
        if (!empty($BetterThatAttributes)) {

            $BetterThatAttributes = $this->mergeAttributes($BetterThatAttributes, 'name');
            $requiredAttributes = $optionalAttributes = [];

            foreach ($BetterThatAttributes as $BetterThatAttribute_key => $BetterThatAttribute_value) {
                if (isset($BetterThatAttribute_value['delete']) and $BetterThatAttribute_value['delete']) {
                     continue;
                }

                if (isset($BetterThatAttribute_value['isMandatory']) and $BetterThatAttribute_value['isMandatory'] == 1) {
                    $requiredAttributes[$BetterThatAttribute_key] = $BetterThatAttribute_value;
                } else {
                    $optionalAttributes[$BetterThatAttribute_key] = $BetterThatAttribute_value;
                    $optionalAttributes[$BetterThatAttribute_key]['isMandatory'] = 0;
                }
            }
            $this->data->setData('profile_required_attributes', json_encode($requiredAttributes));
            $this->data->setData('profile_optional_attributes', json_encode($optionalAttributes));
        }
        //$this->data->addData($offer_information);
        $this->data->addData($generalInformation);

        if (isset($BetterThat)) {
            $this->data->setData('profile_categories', json_encode($BetterThat));
            $this->data->setData('profile_category', end($BetterThat));
        }


        if (isset($store_categories['magento_category'])) {
            $this->data->setData('magento_category', json_encode($store_categories['magento_category']));
        }
        if (isset($store_categories['betterthat_category']))
            $this->data->setData('betterthat_categories', json_encode($store_categories['betterthat_category']));


        if (isset($generalInformation['profile_name'])) {
            $this->data->addData($generalInformation);
        }


        if (!$this->data->getProfileCode() or !$this->data->getProfileName()) {
            return false;
        }
        return true;

    }


    /**
     * @param $array
     * @param $key
     * @return array
     */
    private function mergeAttributes($attributes, $key)
    {
        $tempArray = [];
        $i = 0;
        $keyArray = [];

        if (!empty($attributes) and is_array($attributes)) {
            foreach ($attributes as $val) {
                if (isset($val['delete']) and $val['delete']  == 1) {
                    continue;
                }
                if (!in_array($val[$key], $keyArray)) {
                    $keyArray[$val[$key]] = $val[$key];
                    $tempArray[$val[$key]] = $val;
                }
                $i++;
            }
        }

        return $tempArray;
    }

}
