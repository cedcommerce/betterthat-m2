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
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Helper;

/**
 * Directory separator shorthand
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Class Data For Betterthat Authenticated Seller Api
 * @package Ced\Betterthat\Helper
 */
class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ATTRIBUTE_TYPE_SKU = 'sku';

    const ATTRIBUTE_TYPE_NORMAL = 'normal';

    /**
     * Object Manager
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;

    /**
     * Json Parser
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;

    /**
     * Xml Parser
     * @var \Magento\Framework\Convert\Xml
     */
    public $xml;

    /**
     * DirectoryList
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    public $directoryList;

    /**
     * Date/Time
     * @var $dateTime
     */
    public $dateTime;

    /**
     * File Manager
     * @var $fileIo
     */
    public $fileIo;

    /**
     * Betterthat Logger
     * @var \Ced\Betterthat\Helper\Logger
     */
    public $logger;

    /**
     * @var Profile
     */
    public $profileHelper;

    /**
     * Selected Store Id
     * @var $selectedStore
     */
    public $selectedStore;

    /**
     * Api
     * @var $api
     */
    public $config;

    /**
     * @var mixed
     */
    public $registry;

    /**
     * Config Manager
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfigManager;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    public $messageManager;

    /**
     * Feeds Model
     * @var \Ced\Betterthat\Model\FeedsFactory
     */
    public $feeds;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    public $product;

    /**
     * @var string
     */
    public $apiAuthKey;
    public $urlBuilder;
    public $fulfillmentLagTime;
    public $ids = [];
    public $data = [];
    public $offerData = [];
    public $key = 0;
    public $mpn = '';
    public $Betterthat;
    public $stockState;
    public $debugMode;
    public $response;
    public $images;

    /**
     * Product constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Json\Helper\Data $json
     * @param \Magento\Framework\Xml\Generator $generator
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Io\File $fileIo
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\Message\ManagerInterface $manager
     * @param \Magento\Catalog\Model\ProductFactory $product
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     * @param \BetterthatSdk\ProductFactory $Betterthat
     * @param \Ced\Betterthat\Model\FeedsFactory $feedsFactory
     * @param Config $config
     * @param Logger $logger
     * @param Profile $profile
     * @param \BetterthatSdk\Api\Config $BetterthatConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Json\Helper\Data $json,
        \Magento\Framework\Xml\Generator $generator,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Io\File $fileIo,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Message\ManagerInterface $manager,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \BetterthatSdk\ProductFactory $Betterthat,
        \Ced\Betterthat\Model\FeedsFactory $feedsFactory,
        \Ced\Betterthat\Helper\Config $config,
        \Ced\Betterthat\Helper\Logger $logger,
        \Ced\Betterthat\Helper\Profile $profile,
        \BetterthatSdk\Core\Config $BetterthatConfig,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configfactory,
        \Magento\Framework\App\Cache $cache,
        \Magento\Framework\Session\SessionManagerInterface $session
    ) {
        parent::__construct($context);
        $this->objectManager = $objectManager;
        $this->urlBuilder = $url;
        $this->json = $json;
        $this->xml = $generator;
        $this->directoryList = $directoryList;
        $this->fileIo = $fileIo;
        $this->dateTime = $dateTime;
        $this->scopeConfigManager = $context->getScopeConfig();
        $this->messageManager = $manager;
        $this->registry = $registry;
        $this->product = $product;
        $this->stockState = $stockState;
        $this->logger = $logger;
        $this->Betterthat = $Betterthat;
        $this->profileHelper = $profile;
        $this->feeds = $feedsFactory;
        $this->config = $config;
        $this->_prodAction = $productAction;
        $this->prodCollection = $productFactory;
        $this->configProduct = $configfactory;
        $this->cache = $cache;
        $this->session = $session;
        $this->selectedStore = $config->getStore();
        $this->debugMode = $BetterthatConfig->getDebugMode();
    }

    /**
     * Load File
     * @param string $path
     * @param string $code
     * @return mixed|string
     * @deprecated
     */
    public function loadFile($path, $code = '')
    {
        try {
            if (!empty($code)) {
                $path = $this->directoryList->getPath($code) . "/" . $path;
            }

            if ($this->fileIo->fileExists($path)) {
                $pathInfo = pathinfo($path);
                if ($pathInfo['extension'] == 'json') {
                    $myfile = fopen($path, "r");
                    $data = fread($myfile, filesize($path));
                    fclose($myfile);
                    if (!empty($data)) {
                        try {
                            $data = $this->json->jsonDecode($data);
                            return $data;
                        } catch (\Exception $e) {
                            $this->logger->error('Load File', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                        }
                    }
                }
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->error('Load File', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * Create Json/Xml File
     * @param string|[] $data associative array to be converted into json or xml file
     * @param string|[] $params
     * 'type': file type json or xml, default json,
     * 'name': file name,
     * 'path': path to save file, default 'var/Betterthat'
     * 'code': directory code, default 'var'
     * @return boolean
     * @deprecated
     */
    public function createFile($data, $params = [])
    {
        $type = 'json';
        $timestamp = $this->objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime');
        $name = 'Betterthat_' . $timestamp->gmtTimestamp();
        $path = 'Betterthat';
        $code = 'var';

        if (isset($params['type'])) {
            $type = $params['type'];
        }
        if (isset($params['name'])) {
            $name = $params['name'];
        }
        if (isset($params['path'])) {
            $path = $params['path'];
        }
        if (isset($params['code'])) {
            $code = $params['code'];
        }

        if ($type == 'xml') {
            $xmltoarray = $this->objectManager->create('Magento\Framework\Convert\ConvertArray');
            $data = $xmltoarray->assocToXml($data);
        } elseif ($type == 'json') {
            $data = $this->json->jsonEncode($data);
        } elseif ($type == 'string') {
            $data = ($data);
        }

        $dir = $this->createDir($path, $code);
        $filePath = $dir['path'];
        $fileName = $name . "." . $type;
        try {
            $this->fileIo->write($filePath . "/" . $fileName, $data);
        } catch (\Exception $e) {
            $this->logger->error('Create File', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }

        return true;
    }

    /**
     * Create Betterthat directory in the specified root directory.
     * used for storing json/xml files to be synced.
     * @param string $name
     * @param string $code
     * @return array|string
     */
    public function createDir($name = 'Betterthat', $code = 'var')
    {
        $path = $this->directoryList->getPath($code) . "/" . $name;
        if ($this->fileIo->fileExists($path)) {
            return ['status' => true, 'path' => $path, 'action' => 'dir_exists'];
        } else {
            try {
                $this->fileIo->mkdir($path, 0775, true);
                return ['status' => true, 'path' => $path, 'action' => 'dir_created'];
            } catch (\Exception $e) {
                return $code . '/' . $name . "Directory Creation Failed.";
            }
        }
    }

    /**
     * Create/Update Product on Betterthat
     * @param [] $ids
     * @return bool
     */
    public function createProducts($ids = [])
    {
        try {
            $response = false;
            $ids = $this->validateAllProducts($ids);
            if (!empty($ids['simple']) or !empty($ids['configurable'])) {
                $this->ids = [];
                $this->key = 0;
                $this->data = [];
                $this->prepareSimpleProducts($ids['simple']);
                $this->prepareConfigurableProducts($ids['configurable']);

                $response = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->createProduct($this->data);
                if (@$response['message'] &&
                    in_array($response['message'],["product already exists","Product imported successfully!"]))
                 {
                     $this->response = @$response['data'];
                     $this->updateStatus($this->ids, \Ced\Betterthat\Model\Source\Product\Status::UPLOADED);
                } else {
                    $this->response = @$response['data'];
                    $this->updateStatus($this->ids, \Ced\Betterthat\Model\Source\Product\Status::INVALID);
                }
                //$this->saveResponse($response);
                return $response;
            }
            return $response;
        } catch (\Exception $e) {
            $this->logger->error('Create Product', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }



    /**
     * Create/Update Product on Betterthat
     * @param [] $ids
     * @return bool
     */
    public function updateProducts($ids = [])
    {
        try {
            $response = false;
            $ids = $this->validateAllProducts($ids);
            if (!empty($ids['simple']) or !empty($ids['configurable'])) {
                $this->ids = [];
                $this->key = 0;
                $this->data = [];
                $this->prepareSimpleProducts($ids['simple']);
                $this->prepareConfigurableProducts($ids['configurable']);
                $response = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->updateProduct($this->data);
                if ($response and
                    $response->getStatus() == \BetterthatSdk\Api\Response::REQUEST_STATUS_SUCCESS and
                    empty($response->getError())
                ) {
                    $this->updateStatus($this->ids, \Ced\Betterthat\Model\Source\Product\Status::LIVE);
                } else {
                    $this->updateStatus($this->ids, \Ced\Betterthat\Model\Source\Product\Status::INVALID);
                }
                $response = $this->saveResponse($response);
                return $response;
            }
            return $response;
        } catch (\Exception $e) {
            $this->logger->error('Validate/Create/Update Product', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * Validate All Products
     * @param array $ids
     * @return array
     */
    public function validateAllProducts($ids = [])
    {
        try {
            $validatedProducts = [
                'simple' => [],
                'configurable' => [],
            ];
            $this->ids = [];

            foreach ($ids as $id) {
                $product = $this->product->create()->load($id)->setStoreId($this->selectedStore);
                $profile_id = $product->getBetterthatProfileId();
                // get profile
                $productParents = $this->objectManager
                    ->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')
                    ->getParentIdsByChild($product->getId());
                if (!empty($productParents)) {
                    $profile = $this->profileHelper->getProfile($productParents[0]);
                    if (!empty($profile)) {
                        $product = $this->product->create()->load($productParents[0])
                            ->setStoreId($this->selectedStore);
                    } else {
                        $validatedProducts['errors'][$product->getSku()] =
                            'Please assign product to a profile and try again.';
                        $profile = $this->profileHelper->getProfile($id);
                    }
                } else {
                    $profile = $this->profileHelper->getProfile($id);
                    if (empty($profile)) {
                        $validatedProducts['errors'][$product->getSku()] =
                            'Please assign product to a profile and try again.';
                        continue;
                    }
                }

                // case 1 : for config products
                if ($product->getTypeId() == 'configurable' &&
                    $product->getVisibility() != 1
                ) {
                    $uploadConfigAsSimple = false;
                    $uploadAsSimple = $this->config->getConfigAsSimple();
                    $skipAttributes = $this->config->getSkipValidationAttributes();
                    $skipAttributes = array_flip($skipAttributes);
                    $fromParentAttrs = $this->config->getFromParentAttributes();
                    $configurableProduct = $product;
                    $sku = $configurableProduct->getSku();
                    $parentId = $configurableProduct->getId();
                    $productType = $configurableProduct->getTypeInstance();
                    $products = $productType->getUsedProducts($configurableProduct);
                    $variantAttributes = $productType->getConfigurableAttributesAsArray($configurableProduct);

                    $errors = [
                        $sku => [
                            'sku' => $sku,
                            'id' => $configurableProduct->getId(),
                            'url' => $this->urlBuilder
                                ->getUrl('catalog/product/edit', ['id' => $configurableProduct->getId()]),
                            'errors' => []
                        ]
                    ];

                    //common attributes check start
                    $commonErrors = [];
                    $reqAttributes = $profile->getRequiredAttributes(self::ATTRIBUTE_TYPE_NORMAL);
                    foreach ($reqAttributes as $attributeId => $validationAttribute) {
                        $value = $configurableProduct->getData($validationAttribute['magento_attribute_code']);

                        if ((!isset($value) || empty($value)) && !$validationAttribute['default'] ) {
                            $commonErrors[$attributeId] = 'Common required attribute empty.';
                        }
                    }
                    if (!empty($commonErrors)) {
                        $errors[$sku]['errors'][] = $commonErrors;
                    }

                    //common attributes check end.

                    $key = 0;
                    if (empty($products))
                        $errors[$configurableProduct->getSku()]['errors'][]['Configurable'] = ['Product has no variation in it.'];

                    /*if ((!isset($BetterthatVariantAttributes['variant-size-value']) && !isset($BetterthatVariantAttributes['variant-colour-value'])) && $uploadAsSimple == '1') {
                        $uploadConfigAsSimple = true;
                    }*/

                    foreach ($products as $product) {
                        $modifiedParentSKU = $sku;
                        $errors[$product->getSku()] = [
                            'sku' => $product->getSku(),
                            'id' => $product->getId(),
                            'url' => $this->urlBuilder
                                ->getUrl('catalog/product/edit', ['id' => $product->getId()]),
                            'errors' => []
                        ];

                        $product = $this->product->create()
                            ->setStoreId($this->selectedStore)
                            ->load($product->getId());

                        $profileAttributes = $reqAttributes;
                        foreach ($fromParentAttrs as $fromParentAttr) {
                            $magentoAttr = isset($profileAttributes[$fromParentAttr]) ? $profileAttributes[$fromParentAttr]['magento_attribute_code'] : '';
                            if (!empty($magentoAttr)) {
                                $configProdValue = $configurableProduct->getData($magentoAttr);
                                if (!empty($magentoAttr) && $configProdValue) {
                                    $product->setData($magentoAttr, $configProdValue);
                                }
                            }
                        }
                        $productId = $this->validateProduct($product->getId(), $product, $profile, $parentId);
                        // variant attribute option value check start.
                        foreach ($variantAttributes as $attributes) {
                                $value = $product->getData($attributes['attribute_code']);
                                if(!$value){
                                    $errors[$product->getSku()]['errors'][]['variant-size/color-value'] = 'Variant attribute '.$attributes['attribute_code'].' has no value.';
                                }
                        }



                        // variant attribute option value check end.

                        if (isset($productId['id']) and
                            empty($errors[$sku]['errors']) and
                            empty($errors[$product->getSku()]['errors'])
                        ) {
                            //Check if all mappedAttributes are mapped

                            if (empty($unmappedVariantAttribute)) {
                                $validatedProducts['configurable'][$parentId][$product->getId()]['id'] = $productId['id'];
                                $validatedProducts['configurable'][$parentId][$product->getId()]['type'] = 'configurable';
                                $validatedProducts['configurable'][$parentId][$product->getId()]['variantid'] = '';
                                $validatedProducts['configurable'][$parentId][$product->getId()]['parentid'] = $parentId;
                                $validatedProducts['configurable'][$parentId][$product->getId()]['variantattr'] =
                                    [];
                                $validatedProducts['configurable'][$parentId][$product->getId()]['variantattrmapped'] =
                                    [];
                                $validatedProducts['configurable'][$parentId][$product->getId()]['isprimary'] = 'false';
                                $validatedProducts['configurable'][$parentId][$product->getId()]['isprimary'] = 'false';
                                $validatedProducts['configurable'][$parentId][$product->getId()]['category'] =
                                    $profile->getData('betterthat_categories');
                                $validatedProducts['configurable'][$parentId][$product->getId()]['profile_id'] =
                                    $profile->getId();
                                $validatedProducts['configurable'][$parentId][$product->getId()]['upload_as_simple'] = ($uploadConfigAsSimple == true) ? 'true' : 'false';
                                if ($key == 0) {
                                    $validatedProducts['configurable'][$parentId][$product->getId()]['isprimary'] = 'true';
                                    $key = 1;
                                }
                                $product->setData('betterthat_validation_errors', '["valid"]');
                                $product->getResource()->saveAttribute($product, 'betterthat_validation_errors');
                                continue;
                            } else {
                                $errorIndex = implode(", ", $unmappedVariantAttribute);
                                $errors[$product->getSku()]['errors'][][$errorIndex] = [
                                    'Configurable attributes not mapped.'];
                            }
                        } elseif (isset($productId['errors'])) {
                            $errors[$product->getSku()]['errors'][] = $productId['errors'];
                            $childError = [];

                            if (isset($productId['errors']['sku']) && isset($productId['errors']['errors'])) {
                                $childError[$productId['errors']['sku']]['errors'][] = $productId['errors']['errors'];
                                $product->setbetterthat_validation_errors($this->json->jsonEncode($childError));
                                $product->getResource()->saveAttribute($product, 'betterthat_validation_errors');
                            }

                        }
                    }

                    if (!empty($errors)) {
                        $errorsInRegistry = $this->registry->registry('betterthat_product_validaton_errors');
                        $this->registry->unregister('betterthat_product_validaton_errors');
                        $this->registry->register(
                            'betterthat_product_validaton_errors',
                            is_array($errorsInRegistry) ? array_merge($errorsInRegistry, $errors) : $errors
                        );
                        $configurableProduct->setbetterthat_validation_errors($this->json->jsonEncode($errors));
                        $configurableProduct->getResource()
                            ->saveAttribute($configurableProduct, 'betterthat_validation_errors');
                    } else {
                        $configurableProduct->setbetterthat_validation_errors('["valid"]');
                        $configurableProduct->getResource()
                            ->saveAttribute($configurableProduct, 'betterthat_validation_errors');
                    }
                } elseif (($product->getTypeId() == 'simple') && ($product->getVisibility() != 1)) {

                    // case 2 : for simple products
                    $productId = $this->validateProduct($product->getId(), $product, $profile);

                    if (isset($productId['id'])) {
                        $validatedProducts['simple'][$product->getId()] = [
                            'id' => $productId['id'],
                            'type' => 'simple',
                            'variantid' => null,
                            'variantattr' => null,
                            'category' => $profile->getBetterThatCategory(),
                            'profile_id' => $profile->getId()
                        ];
                    } elseif (isset($productId['errors']) and is_array($productId['errors'])) {
                        $errors[$product->getSku()] = [
                            'sku' => $product->getSku(),
                            'id' => $product->getId(),
                            'url' => $this->urlBuilder
                                ->getUrl('catalog/product/edit', ['id' => $product->getId()]),
                            'errors' => $productId['errors']
                        ];
                        $errorsInRegistry = $this->registry->registry('betterthat_product_validaton_errors');
                        $this->registry->unregister('betterthat_product_validaton_errors');
                        $this->registry->register(
                            'betterthat_product_validaton_errors',
                            is_array($errorsInRegistry) ? array_merge($errorsInRegistry, $errors) :
                                $errors
                        );
                    }
                }
            }

            return $validatedProducts;
        } catch (\Exception $e) {
            $this->logger->error('Validate Product', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * Validate product for availability of required Betterthat product attribute data
     * @param $id
     * @param null $product
     * @param null $profile
     * @param null $parentId
     * @return bool
     */
    public function validateProduct($id, $product = null, $profile = null, $parentId = null)
    {

        try {
            $validatedProduct = false;

            //if product object is not passed, then load in case of Simple product
            if ($product == null) {
                $product = $this->product->create()
                    ->load($id)
                    ->setStoreId($this->selectedStore);
            }

            //if profile is not passed, get profile
            if ($profile == null) {
                $profile = $this->profileHelper->getProfile($product->getId());
            }

            $profileId = $profile->getId();
            $sku = $product->getSku();
            $productArray = $product->toArray();
            $errors = [];
            //Case 1: Profile is Available
            if (isset($profileId) and $profileId != false) {

                $category = $profile->getBetterThatCategory();
                $requiredAttributes = $profile->getRequiredAttributes();
                foreach ($requiredAttributes as $BetterthatAttributeId => $BetterthatAttribute) {

                    $skippedAttribute = [];
                    if (in_array($BetterthatAttributeId, $skippedAttribute)) {
                        // Validation case 1 skip some attributes that are not to be validated.
                        continue;
                    } elseif (!isset($productArray[$BetterthatAttribute['magento_attribute_code']])
                        || empty($productArray[$BetterthatAttribute['magento_attribute_code']]) and
                        empty($BetterthatAttribute['default'])
                    ) {
                        // Validation case 2 Empty or blank value check
                        $errors["$BetterthatAttributeId"] = "Required attribute empty or not mapped. [{$BetterthatAttribute['magento_attribute_code']}]";
                    } elseif (isset($BetterthatAttribute['options']) and
                        !empty($BetterthatAttribute['options'])
                    ) {
                        $valueId = $product->getData($BetterthatAttribute['magento_attribute_code']);
                        $value = "";
                        $defaultValue = "";
                        // Case 2: default value from profile
                        if (isset($BetterthatAttribute['default']) and
                            !empty($BetterthatAttribute['default'])
                        ) {
                            $defaultValue = $BetterthatAttribute['default'];
                        }
                        // Case 3: magento attribute option value
                        $attr = $product->getResource()->getAttribute($BetterthatAttribute['magento_attribute_code']);
                        if ($attr && ($attr->usesSource() || $attr->getData('frontend_input') == 'select')) {
                            $value = $attr->getSource()->getOptionText($valueId);
                            if (is_object($value)) {
                                $value = $value->getText();
                            }
                        }
                        // order of check: default value > option mapping > default magento option value
                        if (!isset($BetterthatAttribute['options'][$defaultValue]) &&
                            !isset($BetterthatAttribute['option_mapping'][$valueId]) &&
                            !isset($BetterthatAttribute['options'][$value])
                        ) {
                            $errors["$BetterthatAttributeId"] = "Betterthat attribute: [" . $BetterthatAttribute['name'] .
                                "] mapped with [" . $BetterthatAttribute['magento_attribute_code'] .
                                "] has invalid option value: <b> " . json_encode($value) . "/" . json_encode($valueId) .
                                "</b> or default value: " . json_encode($defaultValue);
                        }

                    }

                }
                $image = $product->getImage();

                if(!$image || $image == 'no_selection'){
                    $errors['Image'] = 'Product should have Images';
                }

                //Setting Errors in product validation attribute
                if (!empty($errors)) {
                    $validatedProduct['errors'] = $errors;
                    $e = [];
                    $e[$product->getSku()] = [
                        'sku' => $product->getSku(),
                        'id' => $product->getId(),
                        'url' => $this->urlBuilder
                            ->getUrl('catalog/product/edit', ['id' => $product->getId()]),
                        'errors' => [$errors]
                    ];
                    $product->setbetterthat_validation_errors($this->json->jsonEncode($e));
                    $product->getResource()
                        ->saveAttribute($product, 'betterthat_validation_errors');
                } else {
                    // insert product id for status update.
                    $this->ids[] = $product->getId();
                    $product->setData('betterthat_validation_errors', '["valid"]');
                    $product->getResource()
                        ->saveAttribute($product, 'betterthat_validation_errors');
                    $validatedProduct['id'] = $id;
                    $validatedProduct['category'] = $category;
                }
            } else {
                //primebanking@hdfcbank.com
                //customerservices.cards@hdfcbank.com
                //Case 2: Profile is not available, not needed case
                $errors = [
                    "sku" => "$sku",
                    "id" => "$id",
                    "url" => $this->urlBuilder
                        ->getUrl('catalog/product/edit', ['id' => $product->getId()]),
                    "errors" =>
                        [
                            "Profile not found" => "Product or Parent Product is not mapped in any Betterthat profile"
                        ]
                ];
                $validatedProduct['errors'] = $errors;
                $errors = $this->json->jsonEncode([$errors]);
                $product->setData('betterthat_validation_errors', $errors);
                $product->getResource()
                    ->saveAttribute($product, 'betterthat_validation_errors');
            }
            return $validatedProduct;
        } catch (\Exception $e) {
            $this->logger->error('Validate Product', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    private function prepareSimpleProducts($ids = [])
    {
        try {
            $product_array = [];
            $retailer_id = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/retailer_id");
            foreach ($ids as $key => $id) {
                $product = $this->product->create()->load($id['id']);
                $profile = $this->profileHelper->getProfile($product->getId(), $id['profile_id']);
                $categories = json_decode($id['category'],1);
                $this->ids[] = $product->getId();
                $price = $this->getPrice($product);
                $attributes = $this->prepareAttributes(
                    $product,
                    $profile
                );

                $qty = (string)$this->stockState->getStockQty(
                    $product->getId(),
                    $product->getStore()->getWebsiteId()
                );
                /*$price = $this->getPrice($product);
                $data['price'] = $price['price'];*/
                $images = $this->prepareImages($product);
                $product_array = [
                    "id" => $id['id'],
                    "title" => $attributes['title'],
                    "body_html"=> $attributes['body_html'],
                    "retailer_id"=> $retailer_id,
                    "dimensions"=> [
                        "length"=> 0,
                        "height"=> 0,
                        "width"=> 0,
                        "weight"=> 0,
                        "weight_unit"=> "kg"
                    ],
                    "product_categories"=> $categories,
                    "can_be_bundled"=>$attributes['can_be_bundled'],
                    "manufacturer"=>$attributes['can_be_bundled'],
                    "policy_description_option"=>"",
                    "policy_description_val"=>"",
                    "product_shipping_options"=>[$attributes['product_shipping_options']],
                    "shipping_option_charges"=>[$attributes['shipping_option_charges']],
                    "standard_delivery_timeframe"=>$attributes['standard_delivery_timeframe'],
                    "product_return_window"=> $attributes['product_return_window'],
                    "variants" => [
                          [
                              "id"=> $id['id'],
                            "product_id"=> $id['id'],
                            "title"=> $attributes['title'],
                            "price"=> $price['price'],
                            "discounted_price"=>$price['special_price'],
                            "sku"=> "",
                            "position"=> 1,
                            "inventory_policy"=> "deny",
                            "compare_at_price"=> null,
                            "option1"=> $attributes['title'],
                            "option2"=> null,
                            "option3"=> null,
                            "inventory_quantity"=> $qty
                          ]
                        ],
                       "options"=> [
                          [
                              "id" => $id['id'],
                            "product_id" => $id['id'],
                            "name" => "Title",
                            "position" => 1,
                            "values" => [
                                "Default Title"
                            ]
                          ]
                        ],
                    "images"=> $images,
                    "image"=> @$images[1] ? @$images[1] : [],
                ];


                /*"variants"=>[
                    [
                        "id"=>39326433017945,
                        "product_id"=>6573718667353,
      $images                  "title"=>"Blue",
                        "price"=>"0.00",
                        "sku"=>"",
                        "position"=>1,
                        "option1"=>"Blue",
                        "option2"=>null,
                        "option3"=>null,
                        "inventory_quantity"=>10
                    ],
                    [
                        "id"=>39326433050713,
                        "product_id"=>6573718667353,
                        "title"=>"Green",
                        "price"=>"120.00",
                        "sku"=>"",
                        "position"=>2,
                        "option1"=>"Green",
                        "option2"=>null,
                        "option3"=>null,
                        "inventory_quantity"=>9
                    ]
                ],
   "options"=>[
                    [
                        "id"=>8448358580313,
                        "product_id"=>6573718667353,
                        "name"=>"Color",
                        "position"=>1,
                        "values"=>[
                            "Blue",
                            "Green",
                            "Orange",
                            "Yellow",
                            "Red"
                        ]
                    ]
                ],
                */

                $this->data = $product_array;
                return;
            }
        } catch (\Exception $e) {
            $this->logger->error('Create Product', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    //@TODO ADD input type check
    /**
     * Prepare Attributes for Products
     * @param $product
     * @param null $profile
     * @param $type
     * @return array
     */
    private function prepareAttributes($product, $profile = null, $type = "normal")
    {
        try {
            $data = [];
            $mapping = $profile->getAttributes($type);
            if (!empty($mapping)) {
                foreach ($mapping as $id => $attribute) {
                    $productAttributeValue = "";
                    // case 1: default value
                    if (isset($attribute['default']) and
                        !empty($attribute['default'])
                    ) {
                        $productAttributeValue = str_replace("&#39;", "'", $attribute['default']);
                    } else {
                        // case 2: Options
                        // case 2.1: Option mapping value
                        $value = $product->getData($attribute['magento_attribute_code']);
                        $attr = $product->getResource()->getAttribute(
                            $attribute['magento_attribute_code']
                        );

                        if (isset($attribute['option_mapping'][$value]) and
                            is_array($attribute['option_mapping'])
                        ) {
                            $productAttributeValue =
                                str_replace("&#39;", "'", $attribute['option_mapping'][$value]);
                        } elseif ($attr and ($attr->usesSource() || $attr->getData('frontend_input') == 'select')) {
                            // case 2.2: Option value
                            $productAttributeValue =
                                $attr->getSource()
                                    ->getOptionText($product->getData($attribute['magento_attribute_code']));
                            if (is_object($productAttributeValue)) {
                                $productAttributeValue = $productAttributeValue->getText();
                            }
                        } else {
                            $productAttributeValue =
                                str_replace("&#39;", "'", $value);
                        }
                    }

                    if (!empty($productAttributeValue)) {
                        if ($attribute['inputType'] == 'richText') {
                            $data[$id] = '<![CDATA[' . $productAttributeValue . ']]>';
                        } else {
                            $data[$id] = $productAttributeValue;
                        }
                    }
                }
            }

            if ($type == 'sku') {
                $data['quantity'] = (string)$this->stockState->getStockQty(
                    $product->getId(),
                    $product->getStore()->getWebsiteId()
                );
                $price = $this->getPrice($product);
                $data['price'] = $price['price'];
            }
            return $data;
        } catch (\Exception $e) {
            $this->logger->error('Validate/Create Product', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * @param $productObject
     * @return array
     */
    public function getPrice($productObject, $attrValue = null)
    {
        $splprice = (float)$productObject->getFinalPrice();
        $price = (float)$productObject->getPrice();
        if($attrValue != '') {
            $splprice = $price = $attrValue;
        }
        $configPrice = $this->config->getPriceType();
        switch ($configPrice) {
            case 'plus_fixed':
                $fixedPrice = $this->config->getFixedPrice();
                $price = $this->forFixPrice($price, $fixedPrice, 'plus_fixed');
                $splprice = $this->forFixPrice($splprice, $fixedPrice, 'plus_fixed');
                break;

            case 'min_fixed':
                $fixedPrice = $this->config->getFixedPrice();
                $price = $this->forFixPrice($price, $fixedPrice, 'min_fixed');
                $splprice = $this->forFixPrice($splprice, $fixedPrice, 'min_fixed');
                break;

            case 'plus_per':
                $percentPrice = $this->config->getPercentPrice();
                $price = $this->forPerPrice($price, $percentPrice, 'plus_per');
                $splprice = $this->forPerPrice($splprice, $percentPrice, 'plus_per');
                break;

            case 'min_per':
                $percentPrice = $this->config->getPercentPrice();
                $price = $this->forPerPrice($price, $percentPrice, 'min_per');
                $splprice = $this->forPerPrice($splprice, $percentPrice, 'min_per');
                break;

            case 'differ':
                $customPriceAttr = $this->config->getDifferPrice();
                try {
                    $cprice = (float)$productObject->getData($customPriceAttr);
                } catch (\Exception $e) {
                    $this->_logger->debug(" Betterthat: Product Helper: getBetterthatPrice() : " . $e->getMessage());
                }
                $price = (isset($cprice) && $cprice != 0) ? $cprice : $price;
                $splprice = $price;
                break;

            default:
                return [
                    'price' => (string)$price,
                    'special_price' => (string)$splprice,
                ];
        }
        return [
            'price' => (string)$price,
            'special_price' => (string)$splprice,
        ];
    }

    /**
     * ForFixPrice
     * @param null $price
     * @param null $fixedPrice
     * @param string $configPrice
     * @return float|null
     */
    public function forFixPrice($price = null, $fixedPrice = null, $configPrice)
    {
        if (is_numeric($fixedPrice) && ($fixedPrice != '')) {
            $fixedPrice = (float)$fixedPrice;
            if ($fixedPrice > 0) {
                $price = $configPrice == 'plus_fixed' ? (float)($price + $fixedPrice)
                    : (float)($price - $fixedPrice);
            }
        }
        return $price;
    }

    /**
     * ForPerPrice
     * @param null $price
     * @param null $percentPrice
     * @param string $configPrice
     * @return float|null
     */
    public function forPerPrice($price = null, $percentPrice = null, $configPrice)
    {
        if (is_numeric($percentPrice)) {
            $percentPrice = (float)$percentPrice;
            if ($percentPrice > 0) {
                $price = $configPrice == 'plus_per' ?
                    (float)($price + (($price / 100) * $percentPrice))
                    : (float)($price - (($price / 100) * $percentPrice));
            }
        }
        return $price;
    }



    /**
     * Prepare prepareConfigImages
     * @param Object $product
     * @return string|array
     */
    private function prepareConfigImages($product)
    {
        try {
            $productImages = $product->getMediaGalleryImages();
            $images = [];
            if ($productImages->getSize() > 0) {
                $image_index = 1;
                foreach ($productImages as $key => $image) {

                    if ($image_index > 6) {
                        break;
                    }
                    if ($image && $image->getUrl()) {
                        if ($handle = fopen($image->getUrl(), 'r')) {
                            //list($prodWidth, $prodHeight) = getimagesize($image->getUrl());
                            //if ($prodWidth > 450 && $prodHeight > 367) {
                            $this->images[] =
                                [
                                    "id" => $product->getId(),
                                    "product_id" => $product->getId(),
                                    "position" => $image_index,
                                    "alt" => null,
                                    "src" => $image->getUrl(),
                                    "variant_ids" => []
                                ];
                            $image_index++;
                            //}
                        }
                    }
                }
            }
            return $this->images;
        } catch (\Exception $e) {
            $this->logger->error('Validate/Create Product Images Prepare', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }



    /**
     * Prepare images
     * @param Object $product
     * @return string|array
     */
    private function prepareImages($product)
    {
        try {
            $productImages = $product->getMediaGalleryImages();
            $images = [];
            if ($productImages->getSize() > 0) {
                $image_index = 1;
                foreach ($productImages as $key => $image) {

                    if ($image_index > 6) {
                        break;
                    }
                    if ($image && $image->getUrl()) {
                        if ($handle = fopen($image->getUrl(), 'r')) {
                            //list($prodWidth, $prodHeight) = getimagesize($image->getUrl());
                            //if ($prodWidth > 450 && $prodHeight > 367) {
                                $images[] =
                                    [
                                        "id" => $product->getId(),
                                        "product_id" => $product->getId(),
                                        "position" => $image_index,
                                        "alt" => null,
                                        "src" => $image->getUrl(),
                                        "variant_ids" => []
                                    ];
                                $image_index++;
                            //}
                        }
                    }
                }
            }
            return $images;
        } catch (\Exception $e) {
            $this->logger->error('Validate/Create Product Images Prepare', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    private function prepareConfigurableProducts($ids = [])
    {
        try {
            $retailer_id = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/retailer_id");
            foreach ($ids as $parentId => $id) {
                $product = $this->product->create()->load($parentId);
                $profile = $this->profileHelper->getProfile($product->getId(), $product->getbetterthat_profile_id());
                $categories = json_decode($profile->getBetterThatCategory(),1);
                $this->ids[] = $product->getId();
                //$price = $this->getPrice($product);
                $attributes = $this->prepareAttributes(
                    $product,
                    $profile
                );

                $productType = $product->getTypeInstance();
                $variantAttributes = $productType->getConfigurableAttributesAsArray($product);
                /*$qty = (string)$this->stockState->getStockQty(
                    $product->getId(),
                    $product->getStore()->getWebsiteId()
                );*/
                /*$price = $this->getPrice($product);
                $data['price'] = $price['price'];*/
                $parentId = (string)$parentId;
                $collectVariant = $this->prepareVariants($ids[$parentId],$profile,$variantAttributes,$parentId);
                $this->data = [
                    "id" => $parentId,
                    "title" => $attributes['title'],
                    "body_html" => $attributes['body_html'],
                    "retailer_id" => $retailer_id,
                    "dimensions" => [
                        "length" => 0,
                        "height" => 0,
                        "width" => 0,
                        "weight" => 0,
                        "weight_unit" => "kg"
                    ],
                    "product_categories" => $categories,
                    "slug" => @$attributes['slug'],
                    "can_be_bundled" => @$attributes['can_be_bundled'],
                    "manufacturer" => @$attributes['can_be_bundled'],
                    "policy_description_option" => "",
                    "policy_description_val" => "",
                    "product_shipping_options" => [@$attributes['product_shipping_options']],
                    "shipping_option_charges" => [@$attributes['shipping_option_charges']],
                    "standard_delivery_timeframe" => @$attributes['standard_delivery_timeframe'],
                    "product_return_window" => @$attributes['product_return_window'],
                    "variants" => @$collectVariant['variant'],
                    "options" => @$collectVariant['variantOptions'],
                    "images" => $this->images,
                    "image" => @$this->images[0] ? @$this->images[0] : [],
                ];
            }

            return $this->data;
        } catch (\Exception $e) {
            $this->logger->error('Create Configurable Product', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * @param array $ids
     * @param null $profile
     * @return array
     */


    public function prepareVariants($ids = [],$profile = null,$variantAttributes ,$parentId ) : array
    {
        $pos = 1;
        $varindex = 0;
        $variant = [];
        $optionsPreparation = [];
        $optionflag = false;
        foreach ($ids as $key => $id){
            $childproduct = $this->product->create()->load($id['id']);
            $this->prepareConfigImages($childproduct);
            $attributes = $this->prepareAttributes(
                $childproduct,
                $profile
            );
            $qty = (string)$this->stockState->getStockQty(
                $id['id'],
                $childproduct->getStore()->getWebsiteId()
            );
               $variant[$varindex] = [
                    "id" => $id['id'],
                    "product_id" => $parentId,
                    "title" => $attributes['title'],
                    "price" => $childproduct->getPrice(),
                    "sku" => $childproduct->getSku(),
                    "position" => $pos,
                    "inventory_policy" => "deny",
                    "compare_at_price" => null,
                    "inventory_quantity" => $qty
                ];
            $optionIndex = 1;
            foreach($variantAttributes as  $variantAttribute){
                $variant[$varindex]['option'.$optionIndex] = $childproduct->getAttributeText($variantAttribute['attribute_code']);
                $filteredOptions = array_column($variantAttribute['options'],'label');

                if(!$optionflag){
                    $optionsPreparation[] = [
                        "id" => $id['id'],
                        "product_id" => $parentId,
                        "name" => $variantAttribute['label'],
                        "position" => $optionIndex,
                        "values" => $filteredOptions
                    ];
                }

                $optionIndex++;
            }
            $optionflag = true;
            $varindex++;
            $pos++;
        }

        return $collectVariant = [
            'variant' => $variant,
            'variantOptions' => $optionsPreparation,

        ];

    }
    /**
     * Save Response to db
     * @param array $response
     * @return boolean
     */
    public function saveResponse($responses = [])
    {
        //remove index if already set.
        $this->registry->unregister('Betterthat_product_errors');

        if (is_array($responses)) {

            try {
                foreach ($responses as $response) {
                    $this->registry->unregister('Betterthat_product_errors');
                    $this->registry->register('Betterthat_product_errors', $response);
                    $feedModel = $this->feeds->create();
                    $feedModel->addData([
                        'feed_id' => $response['feed_id'],
                        'type' => $response['feed_type'],
                        'feed_response' => $this->json->jsonEncode(
                            ['Body' => $response, 'Errors' => $response]
                        ),
                        'status' => (string)$response['feed_status'],
                        'feed_file' => $response['feed_file'],
                        'response_file' => $response['feed_file'],
                        'feed_created_date' => $this->dateTime->date("Y-m-d"),
                        'feed_executed_date' => $this->dateTime->date("Y-m-d"),
                        'product_ids' => $this->json->jsonEncode($this->ids)
                    ]);
                    $feedModel->save();

                    foreach ($this->ids as $id) {
                        $product = $this->product->create()->load($id);
                        if (isset($product)) {
                            $product->setBetterthatFeedErrors($this->json->jsonEncode($response));
                            $product->getResource()
                                ->saveAttribute($product, 'Betterthat_feed_errors');
                        }
                    }
                }

                return true;
            } catch (\Exception $e) {
                $this->logger->error('Save Product/Offer Response', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
        }
        return false;
    }

    public function deleteProducts($ids = [])
    {
        $response = false;
        if (!empty($ids)) {
            $this->data = [
                0 => [
                    'Product' => [
                        '_attribute' => [],
                        '_value' => [
                            0 => [
                                'Skus' => [
                                    '_attribute' => [],
                                    '_value' => [
                                        0 => [
                                            'Sku' => [
                                                '_attribute' => [],
                                                '_value' => [
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]

                ]
            ];

            foreach ($ids as $id) {
                $product = $this->product->create()->load($id);
                // configurable Product
                if ($product->getTypeId() == 'configurable' &&
                    $product->getVisibility() != 1
                ) {
                    $configurableProduct = $product;
                    $productType = $configurableProduct->getTypeInstance();
                    $products = $productType->getUsedProducts($configurableProduct);
                    foreach ($products as $product) {
                        $this->data[0]['Product']['_value'][0]['Skus']['_value'][0]['Sku']['_value'][]['SellerSku'] = (string)$product->getSku();
                    }
                } elseif ($product->getTypeId() == 'simple' &&
                    $product->getVisibility() != 1
                ) {
                    $this->data[0]['Product']['_value'][0]['Skus']['_value'][0]['Sku']['_value'][]['SellerSku'] = (string)($product->getSku());
                }
            }
            $response = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->deleteProduct($this->data);
            if ($response and
                $response->getStatus() == \BetterthatSdk\Api\Response::REQUEST_STATUS_SUCCESS and
                empty($response->getError())) {
                $this->updateStatus($this->ids, \Ced\Betterthat\Model\Source\Product\Status::NOT_UPLOADED);
            }

            $response = $this->saveResponse($response);
            return $response;
        }
        return $response;
    }

    /**
     * @return bool
     */
    public function updatePriceInventory($ids = [], $withProducts = false, $makeInactive = false)
    {
        try {
            $fromParentAttrs = $this->config->getFromParentAttributes();
            $index = 0;
            $response = false;
            if (!empty($ids)) {
                $this->ids = [];
                $this->data = [
                    0 => [
                        'offer' => []
                    ]
                ];
                $threshold_status = $this->config->getThresholdStatus();
                $threshold_limit = $this->config->getThresholdLimit();
                $threshold_min = $this->config->getThresholdLimitMin();
                $threshold_max = $this->config->getThresholdLimitMax();
                foreach ($ids as $origIndex => $id) {
                    $product = $this->product->create()->load($id);
                    $profile = $this->profileHelper->getProfile($product->getId());

                    // configurable Product
                    if ($product->getTypeId() == 'configurable' &&
                        $product->getVisibility() != 1
                    ) {
                        $this->ids[] = $product->getId();
                        $configurableProduct = $product;
                        $productType = $configurableProduct->getTypeInstance();
                        $products = $productType->getUsedProducts($configurableProduct);
                        $cindex = $index;
                        $quantity = $this->getFinalQuantityToUpload($product);
                        foreach ($products as $product) {
                            $product = $this->product->create()->load($product->getId());
                            $invupdatejson = '{
                                "product_id": "",
                                "_id": "",
                                "base_stock": "$quantity"
                            }';
                            $cindex++;
                        }
                        $index = $cindex;
                    } elseif ($product->getTypeId() == 'simple' &&
                        $product->getVisibility() != 1
                    ) {
                        $this->ids[] = $product->getId();
                        $price = $this->getPrice($product);
                        $quantity = $this->getFinalQuantityToUpload($product);
                        $betterThatId = explode(':' , $product->getBetterthatProductId());
                        $product_id = @$betterThatId[0] ? $betterThatId[0] : '';
                        $_id = @$betterThatId[1] ? $betterThatId[1] : '';;
                        $variant_id = @$betterThatId[2] ? $betterThatId[2] : '';;
                        $invupdate = [
                            "products"=> [
                            [
                                "product_id" => $product_id,
                                "retailer_product_id" => $_id,
                                "stocks"=> [
                                    [
                                        "variant_id"=> $variant_id,
                                        "stock"=> $quantity,
                                        "buy_price"=> @$price['price'],
                                        "discounted_price"=> ""
                                    ]
                                ]
                            ]
                        ]
                        ];
                       $response = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->updateInventory($invupdate);

                    }
                        $index++;
                    }
                }

                return $response;
            }catch (\Exception $e) {
            $this->logger->error('Offer Update', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
            return $response;

    }

    /**
     * Update Product Status
     * @param string $status
     * @return bool
     */
    public function updateStatus($ids = [], $status = \Ced\Betterthat\Model\Source\Product\Status::UPLOADED)
    {

        $betterthatId = null;
        if (!empty($ids) and is_array($ids) and
                in_array($status, \Ced\Betterthat\Model\Source\Product\Status::STATUS)
        ) {
            if(@$this->response['_id']) {
                $betterthatId = $this->response['_id'] .':'. $this->response['retailer_products'][0]['_id'] .':'.$this->response['variants'][0]['_id'] ;
            }
            try{
                foreach ($ids as $index => $product) {
                    $product = $this->product->create()->load($product);
                    $product->setData('betterthat_product_status', $status);
                    $product->getResource()->saveAttribute($product, 'betterthat_product_status');
                    if($betterthatId){
                        $product->setData('betterthat_product_id', $betterthatId);
                        $product->getResource()->saveAttribute($product, 'betterthat_product_id');
                    }
                }
                return true;

            }catch(\Exception $e){

            }

        }

        return false;
    }

    /**
     * Check if configurations are valid
     * @return boolean
     */
    public function checkForConfiguration()
    {
        return true;
        return $this->config->isValid();
    }

    public function getProductReference($type='',$product='')
    {
        if($type=='type'){
            $type = $this->config->getReferenceType();
            return $type;
        }
        if($type=='value'){
            $type = $this->config->getReferenceValue();
            if($type) {
                $attr = $product->getResource()->getAttribute($type);
                $refType = $this->config->getReferenceType();
                if ($attr) {
                    if ($attr->getSourceModel($attr) || $attr->getData('frontend_input') == 'select') {
                            $valueId =  $product->getData($type);
                            $value = $attr->getSource()->getOptionText($valueId);
                            $result = $this->validateProductId($value, $refType);
                            if($result)
                            return $value;
                            return $result;
                        } else {
                           $value =  $product->getData($type);
                           $result = $this->validateProductId($value, $refType);
                           if($result)
                            return $value;
                           return $result;
                        }
                    } else {
                           $value =  $product->getData($type);
                           $result = $this->validateProductId($value, $refType);
                           if($result)
                            return $value;
                           return $result;
                    }

            }

        }
        return false;
    }

    /**
     * @param $productID
     * @param $barcodeType
     * @return bool
     *
     */
    public function validateProductId($productID, $barcodeType) {
        if(!in_array($barcodeType, array('upc', 'ean', 'isbn', 'mpn'))) {
            return false;
        }
        if($barcodeType == 'mpn') {
            return true;
        }
        if (preg_match('/[^0-9]/', $productID))
        {
            // is not numeric
            return false;
        }
        // pad with zeros to lengthen to 14 digits
        switch (strlen($productID))
        {
            case 8:
                $productID = "000000".$productID;
                break;
            case 12:
                $productID = "00".$productID;
                break;
            case 13:
                $productID = "0".$productID;
                break;
            case 14:
                break;
            default:
                // wrong number of digits
                return false;
        }
        // calculate check digit
        $a = array();
        $a[0] = (int)($productID[0]) * 3;
        $a[1] = (int)($productID[1]);
        $a[2] = (int)($productID[2]) * 3;
        $a[3] = (int)($productID[3]);
        $a[4] = (int)($productID[4]) * 3;
        $a[5] = (int)($productID[5]);
        $a[6] = (int)($productID[6]) * 3;
        $a[7] = (int)($productID[7]);
        $a[8] = (int)($productID[8]) * 3;
        $a[9] = (int)($productID[9]);
        $a[10] = (int)($productID[10]) * 3;
        $a[11] = (int)($productID[11]);
        $a[12] = (int)($productID[12]) * 3;
        $sum = $a[0] + $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6] + $a[7] + $a[8] + $a[9] + $a[10] + $a[11] + $a[12];
        $check = (10 - ($sum % 10)) % 10;
        // evaluate check digit
        $last = (int)($productID[13]);
        return $check == $last;
    }


    /**
     * Get Feeds, Get Single Feed, Get Single Feed with Error Details
     * @param null $feedId
     * @param string $subUrl
     * @return array|boolean
     * @link  https://www.Betterthatcommerceservices.com/question/current-xml-puts-and-gets/
     */
    public function getFeeds($feedId = null, $feedData = null)
    {
        //$response = null;
        try {
            if($feedData != null) {
                if (is_array($feedData)) {
                    reset($feedData); // make sure array pointer is at first element
                    $firstKey = key($feedData);
                }
                if (isset($feedData[$firstKey]['has_error_report']) && ($feedData[$firstKey]['has_error_report'] == 'true')) {
                    if($firstKey == 'product_import_tracking') {
                        $feedData = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getFeeds($feedId ,\BetterthatSdk\Core\Request::POST_ITEMS_SUB_URL.'/'.$feedId.'/'.'error_report');
                    } else {
                        $feedData = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getFeeds($feedId ,\BetterthatSdk\Core\Request::POST_OFFER_IMPORT.'/'.$feedId.'/'.'error_report');
                    }
                } elseif (isset($feedData[$firstKey]['has_new_product_report']) && ($feedData[$firstKey]['has_new_product_report'] == 'true')) {
                    $feedData = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getFeeds($feedId ,\BetterthatSdk\Core\Request::POST_ITEMS_SUB_URL.'/'.$feedId.'/'.'new_product_report');
                } elseif (isset($feedData[$firstKey]['has_transformation_error_report']) && ($feedData[$firstKey]['has_transformation_error_report'] == 'true')) {
                    $feedData = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getFeeds($feedId ,\BetterthatSdk\Core\Request::POST_ITEMS_SUB_URL.'/'.$feedId.'/'.'transformation_error_report');
                } elseif (isset($feedData[$firstKey]['has_transformed_file']) && ($feedData[$firstKey]['has_transformed_file'] == 'true')) {
                    $feedData = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getFeeds($feedId ,\BetterthatSdk\Core\Request::POST_ITEMS_SUB_URL.'/'.$feedId.'/'.'transformed_file');
                }
                return $this->json->jsonEncode($feedData);
            }
        } catch (\Exception $e) {
            $this->logger->error('Get Feeds', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }


    /**
     * Sync Feed
     */
    public function syncFeeds($feed = null)
    {
        try {
            if ($feed and $feed->getId()) {
                if ($feed->getType() == 'inventory-update')
                    $feed_data = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getFeeds($feed->getFeedId(), \BetterthatSdk\Core\Request::POST_OFFER_IMPORT . '/' . $feed->getFeedId());
                else
                    $feed_data = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getFeeds($feed->getFeedId(), \BetterthatSdk\Core\Request::POST_ITEMS_SUB_URL . '/' . $feed->getFeedId());

                $feedError = $this->getFeeds($feed->getFeedId(), $feed_data);
                $productIds = json_decode($feed->getProductIds());
                if (is_array($productIds)) {
                    $productIds = $this->product->create()->getCollection()
                        ->addAttributeToFilter('entity_id', array('in' => $productIds))
                        ->getColumnValues('entity_id');
                    $attrData = array('Betterthat_feed_errors' => $feedError);
                    $storeId = 0;
                    $this->_prodAction->updateAttributes($productIds, $attrData, $storeId);
                }
                if (isset($feed_data['product_import_tracking'])) {
                    $feed_data = $feed_data['product_import_tracking'];
                    if (isset($feed_data['import_status']))
                        $feed_data['status'] = $feed_data['import_status'];
                    $feed->setData('transform_lines_in_error', $feed_data['transform_lines_in_error']);
                    $feed->setData('transform_lines_in_success', $feed_data['transform_lines_in_success']);
                    $feed->setData('transform_lines_read', $feed_data['transform_lines_read']);
                    $feed->setData('transform_lines_with_warning', $feed_data['transform_lines_with_warning']);
                    $feed->setData('status', $feed_data['import_status']);
                    $feed->setData('feed_response', $feedError);
                    $feed->save();
                } else if (isset($feed_data['import'])) {
                    $errorIds = $successIds = [];
                    $feedError = $this->json->jsonDecode($feedError);
                    $skus = isset($feedError['import']['offers']['offer']) ? array_column($feedError['import']['offers']['offer'], 'sku') : array();
                    if (is_array($productIds)) {
                        if (empty($skus)) {
                            $successIds = $productIds;
                        }
                        foreach ($productIds as $productId) {
                            $isChild = false;
                            $prod = $this->prodCollection->create()->load($productId);
                            $productParents = $this->configProduct->create()
                                ->getParentIdsByChild($productId);
                            if (!empty($productParents)) {
                                $isChild = true;
                            }
                            $profile = $this->profileHelper->getProfile($productId);
                            $requiredAttributes = $profile->getRequiredAttributes();
                            if ($profile->getId()) {
                                $mappedSku = isset($requiredAttributes['internal-sku']['magento_attribute_code']) ? $requiredAttributes['internal-sku']['magento_attribute_code'] : '';
                                if (array_search($prod->getData($mappedSku), $skus) !== false) {
                                    $errorIds[] = $productId;
                                    if ($isChild && isset($productParents[0]) && !in_array($productParents[0], $errorIds)) {
                                        $errorIds[] = $productParents[0];
                                    }
                                } else if ($isChild) {
                                    $successIds[] = $productId;
                                }
                            }
                        }

                        $storeId = 0;
                        if (isset($successIds) && is_array($successIds) && count($successIds) > 0) {
                            //$successData = array( 'Betterthat_product_status' => 'LIVE' );
                            //$this->_prodAction->updateAttributes($successIds, $successData, $storeId);
                        }
                        if (isset($errorIds) && is_array($errorIds) && count($errorIds) > 0) {
                            $errorData = array('Betterthat_product_status' => 'INVALID');
                            $this->_prodAction->updateAttributes($errorIds, $errorData, $storeId);
                        }
                    }
                    $feedError = $this->json->jsonEncode($feedError);
                    $feed_data = $feed_data['import'];
                    if (isset($feed_data['import_status']))
                        $feed_data['status'] = $feed_data['status'];
                    $feed->setData('transform_lines_in_error', isset($feed_data['lines_in_error']) ? $feed_data['lines_in_error'] : '');
                    $feed->setData('transform_lines_in_success', isset($feed_data['lines_in_success']) ? $feed_data['lines_in_success'] : '');
                    $feed->setData('transform_lines_read', isset($feed_data['lines_read']) ? $feed_data['lines_read'] : '');
                    $feed->setData('transform_lines_with_warning', isset($feed_data['lines_in_pending']) ? $feed_data['lines_in_pending'] : '');
                    $feed->setData('status', $feed_data['status']);
                    $feed->setData('feed_response', $feedError);
                    $feed->save();
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Sync Feeds', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * Create/Update Product on Betterthat
     * @param [] $ids
     * @return bool
     */
    public function prepareProductData($ids = [])
    {
        try {
            $response = false;
            $ids = $this->validateAllProducts($ids);
            if (!empty($ids['simple']) or !empty($ids['configurable'])) {
                $this->ids = [];
                $this->key = 0;
                $this->data = [];
                $this->prepareSimpleProducts($ids['simple']);
                $this->prepareConfigurableProducts($ids['configurable']);
            }
            return $response;
        } catch (\Exception $e) {
            $this->logger->error('Prepare Offer Product Data', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    public function getProductIdsFromOfferAPI()
    {
        try {
            $excludedProdIds = $this->product->create()->getCollection()
                ->addAttributeToFilter('Betterthat_exclude_from_sync', array('eq' => '1'))
                ->getColumnValues('entity_id');
            if(count($excludedProdIds) <= 0) {
                $excludedProdIds = '';
            }
            $invChunkSize = 100;
            $offsetNumber = $this->cache->load("ced_Betterthat_offset_number");
            $offsetNumber = ($offsetNumber == '') ? 0 : $offsetNumber;
            $offers = $this->Betterthat->create(['config' => $this->config->getApiConfig()])->getOffers($invChunkSize, $offsetNumber);
            $offerWithStatus = array_column($offers, 'active', 'shop_sku');
            $activeProducts = array_keys($offerWithStatus, "true");
            $inActiveProducts = array_keys($offerWithStatus, "false");
            $storeId = 0;
            $activeIds = $this->product->create()->getCollection()
                ->addAttributeToFilter('sku', array('in' => $activeProducts))
                ->getColumnValues('entity_id');
            $liveData = array( 'Betterthat_product_status' => 'LIVE' );
            $this->_prodAction->updateAttributes($activeIds, $liveData, $storeId);
            $inActiveIds = $this->product->create()->getCollection()
                ->addAttributeToFilter('sku', array('in' => $inActiveProducts))
                ->getColumnValues('entity_id');
            $inactiveData = array( 'Betterthat_product_status' => 'INACTIVE' );
            $this->_prodAction->updateAttributes($inActiveIds, $inactiveData, $storeId);
            $offerskus = array_column($offers, 'shop_sku');
            $this->updateParentProductStatus($offerskus);
            if (count($offerskus) > 0) {
                $offsetNumber = $offsetNumber + $invChunkSize;
            } else {
                $offsetNumber = 0;
            }
            $this->cache->save((string)($offsetNumber), "ced_Betterthat_offset_number", array('block_html'), 99999);
            $prodCollection = $this->product->create()->getCollection()
                ->addAttributeToFilter('sku', array('in' => $offerskus))
                ->addAttributeToFilter('entity_id', array('nin' => $excludedProdIds));
            $prodIds = $prodCollection->getColumnValues('entity_id');
        } catch (\Exception $e) {
            $this->logger->addError('Product Ids for error has error', array('path' => __METHOD__, 'exception' => $e->getMessage()));
            return false;
        }
        return $prodIds;
    }

    public function updateParentProductStatus($prodSkus = array())
    {
        try {
            $parentIds = $this->session->getData('parent_ids');
            if(!empty($parentIds)) {
                $parentIds = $this->json->jsonDecode($parentIds);
            } else {
                $parentIds = [];
            }
            $prodCollection = $this->product->create()->getCollection()
                ->addAttributeToFilter('sku', array('in' => $prodSkus));
            foreach ($prodCollection as $prod) {
                $productParents = $this->objectManager
                    ->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')
                    ->getParentIdsByChild($prod->getId());
                if (!empty($productParents)) {
                    $profile = $this->profileHelper->getProfile($productParents[0]);
                    if (!empty($profile)) {
                        $configurableProduct = $this->product->create()->load($productParents[0])
                            ->setStoreId($this->selectedStore);
                        if(!in_array($configurableProduct->getId(), $parentIds)) {
                            if ($configurableProduct->getTypeId() == 'configurable' &&
                                $configurableProduct->getVisibility() != 1
                            ) {
                                $parentIds[] = $configurableProduct->getId();
                                $this->session->setData('parent_ids', $this->json->jsonEncode($parentIds));
                                $productType = $configurableProduct->getTypeInstance();
                                $products = $productType->getUsedProducts($configurableProduct);
                                $BetterthatStatus = [];
                                foreach ($products as $product) {
                                    $product = $this->product->create()->load($product->getId())
                                        ->setStoreId($this->selectedStore);
                                    $BetterthatStatus[] = $product->getBetterthatProductStatus();
                                }
                                $childStatus = array_unique($BetterthatStatus);
                                if (count($childStatus) > 1) {
                                    $configurableProduct->setBetterthatProductStatus('PARTIAL');
                                    $configurableProduct->getResource()->saveAttribute($configurableProduct, 'Betterthat_product_status');
                                } else {
                                    $configurableProduct->setBetterthatProductStatus($childStatus[0]);
                                    $configurableProduct->getResource()->saveAttribute($configurableProduct, 'Betterthat_product_status');
                                }
                            }
                        }
                    }
                }
            }
            $this->session->unsetData('parent_ids');
        } catch (\Exception $e) {
            $this->logger->addError('Product Ids for error has error', array('path' => __METHOD__, 'exception' => $e->getMessage()));
            return false;
        }
        return true;
    }

    public function getChildProductStatus($prodId = array())
    {
        try {
            $configurableProduct = $this->product->create()->load($prodId)
                ->setStoreId($this->selectedStore);
            if ($configurableProduct->getTypeId() == 'configurable') {
                $feedErrors = json_decode($configurableProduct->getBetterthatFeedErrors(), true);
                $feedErrors = isset($feedErrors['import']['offers']['offer']) ? $feedErrors['import']['offers']['offer'] : array();
                if(count($feedErrors) > 0 && !isset($feedErrors[0])) {
                    $feedErrors[] = $feedErrors;
                }
                $feedErrorsWithSku = array_column($feedErrors, 'error-message', 'sku');
                $productType = $configurableProduct->getTypeInstance();
                $products = $productType->getUsedProducts($configurableProduct);
                $BetterthatStatus = [];
                foreach ($products as $product) {
                    $product = $this->product->create()->load($product->getId())
                        ->setStoreId($this->selectedStore);
                    $BetterthatStatus[$product->getSku()] = array('Status' => $product->getBetterthatProductStatus(), 'Error' => isset($feedErrorsWithSku[$product->getSku()]) ? $feedErrorsWithSku[$product->getSku()] : 'Error Not Found');
                }
                return $BetterthatStatus;
            } elseif ($configurableProduct->getTypeId() == 'simple') {
                $feedErrors = json_decode($configurableProduct->getBetterthatFeedErrors(), true);
                $feedErrors = isset($feedErrors['import']['offers']['offer']) ? $feedErrors['import']['offers']['offer'] : array();
                if(count($feedErrors) > 0 && !isset($feedErrors[0])) {
                    $feedErrors[] = $feedErrors;
                }
                $feedErrorsWithSku = array_column($feedErrors, 'error-message', 'sku');
                $BetterthatStatus[$configurableProduct->getSku()] = array('Status' => $configurableProduct->getBetterthatProductStatus(), 'Error' => isset($feedErrorsWithSku[$configurableProduct->getSku()]) ? $feedErrorsWithSku[$configurableProduct->getSku()] : 'Error Not Found');
                return $BetterthatStatus;
            }
        } catch (\Exception $e) {
            $this->logger->addError('Product Ids for error has error', array('path' => __METHOD__, 'exception' => $e->getMessage()));
            return array();
        }
        return array();
    }

    public function getFinalQuantityToUpload($product) {
        $quantity = 0;
        $useMSI = $this->config->getUseMsi();
        if($useMSI) {
            $useSalableQty = $this->config->getUseSalableQty();
            if($useSalableQty) {
                $msiStockName = $this->config->getSalableStockName();
                $getSalableQuantityDataBySku = $this->objectManager->create('Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku');
                $invSourceData = $getSalableQuantityDataBySku->execute($product->getSku());
                if ($invSourceData && is_array($invSourceData) && count($invSourceData) > 0) {
                    $invSourceData = array_column($invSourceData, 'qty', 'stock_name');
                    $quantity = isset($invSourceData[$msiStockName]) ? $invSourceData[$msiStockName] : 0;
                }
            } else {
                $msiSourceCode = $this->config->getMsiSourceCode();
                $msiSourceDataModel = $this->objectManager->create('\Magento\InventoryCatalogAdminUi\Model\GetSourceItemsDataBySku');
                $invSourceData = $msiSourceDataModel->execute($product->getSku());
                if ($invSourceData && is_array($invSourceData) && count($invSourceData) > 0) {
                    $invSourceData = array_column($invSourceData, 'quantity', 'source_code');
                    $quantity = isset($invSourceData[$msiSourceCode]) ? $invSourceData[$msiSourceCode] : 0;
                }
            }
        } else {
            $quantity = (string)$this->stockState->getStockQty(
                $product->getId(),
                $product->getStore()->getWebsiteId()
            );
        }
        return $quantity;
    }
}
