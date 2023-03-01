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

namespace Betterthat\Betterthat\Helper;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const ATTRIBUTE_TYPE_SKU = 'sku';

    public const ATTRIBUTE_TYPE_NORMAL = 'normal';

    /**
     * Object Managerr
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;

    /**
     * Json Parserr
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;

    /**
     * Xml Parserr
     *
     * @var \Magento\Framework\Convert\Xml
     */
    public $xml;

    /**
     * DirectoryListt
     *
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    public $directoryList;

    /**
     * Date/Timee
     *
     * @var $dateTime
     */
    public $dateTime;

    /**
     * File Manager
     *
     * @var $fileIo
     */
    public $fileIo;

    /**
     * Betterthat Logger
     *
     * @var \Betterthat\Betterthat\Helper\Logger
     */
    public $logger;

    /**
     * @var Profile
     */
    public $profileHelper;

    /**
     * Selected Store Id
     *
     * @var $selectedStore
     */
    public $selectedStore;

    /**
     * Api
     *
     * @var $api
     */
    public $config;

    /**
     * @var mixed
     */
    public $registry;

    /**
     * Config Manager
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfigManager;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    public $messageManager;

    /**
     * Feeds Model
     *
     * @var \Betterthat\Betterthat\Model\FeedsFactory
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
    /**
     * @var \Magento\Framework\UrlInterface
     */
    public $urlBuilder;
    /**
     * @var fulfillmentLagTime
     */
    public $fulfillmentLagTime;
    /**
     * @var array
     */
    public $ids = [];
    /**
     * @var array
     */
    public $data = [];
    /**
     * @var array
     */
    public $offerData = [];
    /**
     * @var int
     */
    public $key = 0;
    /**
     * @var string
     */
    public $mpn = '';
    /**
     * @var \BetterthatSdk\ProductFactory
     */
    public $Betterthat;
    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    public $stockState;
    /**
     * @var bool
     */
    public $debugMode;
    /**
     * @var response
     */
    public $response;
    /**
     * @var images
     */
    public $images;

    /**
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
     * @param \Betterthat\Betterthat\Model\FeedsFactory $feedsFactory
     * @param Config $config
     * @param Logger $logger
     * @param Profile $profile
     * @param \BetterthatSdk\Core\Config $BetterthatConfig
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $productAction
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configfactory
     * @param \Magento\Framework\App\Cache $cache
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \Magento\InventoryApi\Api\GetSourceItemsBySkuInterface $stockItemRepository
     * @param \Magento\Indexer\Model\IndexerFactory $indexFactory
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
        \Betterthat\Betterthat\Model\FeedsFactory $feedsFactory,
        \Betterthat\Betterthat\Helper\Config $config,
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Betterthat\Betterthat\Helper\Profile $profile,
        \BetterthatSdk\Core\Config $BetterthatConfig,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configfactory,
        \Magento\Framework\App\Cache $cache,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\InventoryApi\Api\GetSourceItemsBySkuInterface $stockItemRepository,
        \Magento\Indexer\Model\IndexerFactory $indexFactory
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
        $this->stockItemRepository = $stockItemRepository;
        $this->indexFactory = $indexFactory;
    }

    /**
     * SendBetterthatVisibility
     *
     * @param array $data
     * @return false|string
     */
    public function _sendBetterthatVisibility($data)
    {
        return $this->Betterthat
            ->create(['config' => $this->config->getApiConfig()])
            ->_sendBetterthatVisibility($data);
    }

    /**
     * DeleteProduct
     *
     * @param array $data
     * @return array[]
     */
    public function deleteProduct($data)
    {
        if (isset($data["product_id"]) && isset($data['delete_status'])) {
            $product = $this->product->create()->load($data["product_id"]);
            if ($product->getId()) {
                $product->setData("betterthat_validation_errors", '');
                $product->setData("betterthat_visibility", 'no');
                $product->setData("betterthat_product_status", 'DELETED');
                $product->setData("betterthat_product_id", '');
                $product->setData("betterthat_feed_errors", '');
                $product->save();
                return [
                        [
                            'success' => "true",
                            "message" => "Item deleted successfully",
                            "data" => $data
                        ]
                    ];
            } else {
                return [
                        [
                            'success' => "false",
                            "message" => "Item Id not found",
                            "data" => $data
                        ]
                      ];
            }
        } else {
            return [
                [
                    'success' => "false",
                    "message" => "Please enter all required fields [product_id,delete_status]",
                    "data" => $data
                ]
            ];
        }
        return [['success' => "true", "message" => "Item deleted successfully", "data" => $data]];
    }

    /**
     * CreateProducts
     *
     * @param array $ids
     * @return array|false|string
     */
    public function createProducts($ids = [])
    {

        try {
            $ids = $this->validateAllProducts($ids);
            if (!empty($ids['simple']) || !empty($ids['configurable'])) {
                $this->ids = [];
                $this->key = 0;
                $this->data = [];
                if (isset($ids['simple'])) {
                    $this->prepareSimpleProducts($ids['simple']);
                }
                if (isset($ids['configurable'])) {
                    $this->prepareConfigurableProducts($ids['configurable']);
                }
                $response = $this->Betterthat
                    ->create(['config' => $this->config->getApiConfig()])
                    ->createProduct($this->data);
                $this->serverResponse = $response;
                if (isset($response['message'])
                    && in_array(
                        $response['message'],
                        [
                            "Product updated successfully!",
                            "product already exists",
                            "Product imported successfully!",
                            "product already exists in cleanse section."
                        ]
                    )
                ) {
                    $this->response = isset($response['data']) ? $response['data'] : '';
                    $this->updateStatus($this->ids, \Betterthat\Betterthat\Model\Source\Product\Status::UPLOADED);
                } else {
                    $this->response = isset($response['data']) ? $response['data'] : '';
                    $this->updateStatus(
                        $this->ids,
                        \Betterthat\Betterthat\Model\Source\Product\Status::INVALID
                    );
                }
                return $response;
            } elseif (isset($ids['bt_visibility'])) {
                $response['message'] = $response['bt_visibility'] = $ids['bt_visibility'];
            }
        } catch (\Exception $e) {
            $this->logger->error(
                'Create Product',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }
    /**
     * Create/Update Product on Betterthat
     *
     * @param  array $ids
     * @return bool
     */
    public function updateProducts($ids = [])
    {
        try {
            $response = false;
            $ids = $this->validateAllProducts($ids);
            if (!empty($ids['simple']) || !empty($ids['configurable'])) {
                $this->ids = [];
                $this->key = 0;
                $this->data = [];
                $this->prepareSimpleProducts($ids['simple']);
                $this->prepareConfigurableProducts($ids['configurable']);
                $response = $this->Betterthat
                    ->create(['config' => $this->config->getApiConfig()])
                    ->updateProduct($this->data);
                if ($response
                    && $response->getStatus() == \BetterthatSdk\Api\Response::REQUEST_STATUS_SUCCESS
                    && empty($response->getError())
                ) {
                    $this->updateStatus($this->ids, \Betterthat\Betterthat\Model\Source\Product\Status::LIVE);
                } else {
                    $this->updateStatus($this->ids, \Betterthat\Betterthat\Model\Source\Product\Status::INVALID);
                }
                $response = $this->saveResponse($response);
                return $response;
            }
            return $response;
        } catch (\Exception $e) {
            $this->logger->error(
                'Validate/Create/Update Product',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * Validate All Products
     *
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
                $product = $this->product->create()
                    ->load($id)
                    ->setStoreId($this->selectedStore);
                //$profile_id = $product->getBetterthatProfileId();
                // get profile
                $productParents = $this->objectManager
                    ->create(\Magento\ConfigurableProduct\Model\Product\Type\Configurable::class)
                    ->getParentIdsByChild($product->getId());
                if (!empty($productParents)) {
                    $profile = $this->profileHelper
                        ->getProfile($productParents[0]);
                    if (!empty($profile)) {
                        $product = $this->product
                            ->create()
                            ->load($productParents[0])
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

                if ($product->getTypeId() == 'virtual') {
                    continue; // virtual item's are prohibited from BT
                }

                // case 1 : for config products
                if ($product->getTypeId() == 'configurable'
                    && $product->getVisibility() != 1
                ) {
                    //refactor
                    $validatedProducts = $this->validateConfigurableItems($product, $profile);
                } elseif (($product->getTypeId() == 'simple')
                    && ($product->getVisibility() != 1)) {
                    // case 2 : for simple products
                    $productId = $this->validateProduct($product->getId(), $product, $profile);
                    if (isset($productId['bt_visibility'])) {
                        return $productId;
                    }
                    if (isset($productId['id'])) {
                        $validatedProducts['simple'][$product->getId()] = [
                            'id' => $productId['id'],
                            'type' => 'simple',
                            'variantid' => null,
                            'variantattr' => null,
                            'category' => $profile->getBetterThatCategory(),
                            'profile_id' => $profile->getId()
                        ];
                    } elseif (isset($productId['errors']) && is_array($productId['errors'])) {
                        $errors[$product->getSku()] = [
                            'sku' => $product->getSku(),
                            'id' => $product->getId(),
                            'url' => $this->urlBuilder
                                ->getUrl('catalog/product/edit', ['id' => $product->getId()]),
                            'errors' => $productId['errors']
                        ];
                        $errorsInRegistry = $this->registry
                            ->registry('betterthat_product_validaton_errors');
                        $this->registry
                            ->unregister('betterthat_product_validaton_errors');
                        $this->registry
                            ->register(
                                'betterthat_product_validaton_errors',
                                is_array($errorsInRegistry) ?
                                    $this->arrMerge($errorsInRegistry, $errors) :
                                     $errors
                            );
                    }
                }
            }
            return $validatedProducts;
        } catch (\Exception $e) {
            $this->logger->error(
                'Validate Product',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * ValidateConfigurableItems
     *
     * @param mixed $product
     * @param mixed $profile
     * @return array|string[]
     */
    public function validateConfigurableItems($product, $profile)
    {
        $validatedProducts = [];
        $uploadConfigAsSimple = false;
        $uploadAsSimple = $this->config->getConfigAsSimple();
        $skipAttributes = $this->config->getSkipValidationAttributes();
        $skipAttributes = array_flip($skipAttributes);
        $fromParentAttrs = $this->config->getFromParentAttributes();
        $configurableProduct = $product;

        // bt visibility check
        if ($configurableProduct->getBetterthatVisibility() == 0) {
            return ["bt_visibility" => "Item's visibility is not visible hence can't be uploaded"];
        }
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
            $skippedAttribute = ['short_description'];
            if (in_array($validationAttribute['magento_attribute_code'], $skippedAttribute)) {
                // Validation case 1 skip some attributes that are not to be validated.
                continue;
            }
            if ((!isset($value) || empty($value)) && !$validationAttribute['default']) {
                $commonErrors[$attributeId] = 'Common required attribute empty.';
            }
        }
        if (!empty($commonErrors)) {
            $errors[$sku]['errors'][] = $commonErrors;
        }
        //common attributes check end.
        $key = 0;
        if (empty($products)) {
            $errors[$configurableProduct->getSku()]['errors'][]['Configurable']
                = ['Product has no variation in it.'];
        }

        foreach ($products as $product) {
            $modifiedParentSKU = $sku;
            if ($product->getTypeId() == 'virtual') {
                continue; // virtual item's are prohibited from BT
            }
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
                $magentoAttr = isset($profileAttributes[$fromParentAttr])
                    ? $profileAttributes[$fromParentAttr]['magento_attribute_code']
                    : '';
                if (!empty($magentoAttr)) {
                    $configProdValue = $configurableProduct->getData($magentoAttr);
                    if (!empty($magentoAttr) && $configProdValue) {
                        $product->setData($magentoAttr, $configProdValue);
                    }
                }
            }
            $productId = $this
                ->validateProduct($product->getId(), $product, $profile, $parentId);
            // variant attribute option value check start.
            foreach ($variantAttributes as $attributes) {
                $value = $product->getData($attributes['attribute_code']);
                if (!$value) {
                    $errors[$product->getSku()]['errors'][]['variant-size/color-value'] =
                        'Variant attribute ' . $attributes['attribute_code'] . ' has no value.';
                }
            }
            // variant attribute option value check end.
            if (isset($productId['id'])
                && empty($errors[$sku]['errors'])
                && empty($errors[$product->getSku()]['errors'])
            ) {
                //Check if all mappedAttributes are mapped
                if (empty($unmappedVariantAttribute)) {
                    $validatedProducts['configurable'][$parentId][$product->getId()]['id']
                        = $productId['id'];
                    $validatedProducts['configurable'][$parentId][$product->getId()]['type']
                        = 'configurable';
                    $validatedProducts['configurable'][$parentId][$product->getId()]['variantid']
                        = '';
                    $validatedProducts['configurable'][$parentId][$product->getId()]['parentid']
                        = $parentId;
                    $validatedProducts['configurable'][$parentId][$product->getId()]['variantattr']
                        = [];
                    $validatedProducts['configurable'][$parentId][$product->getId()]['variantattrmapped']
                        = [];
                    $validatedProducts['configurable'][$parentId][$product->getId()]['isprimary']
                        = 'false';
                    $validatedProducts['configurable'][$parentId][$product->getId()]['isprimary']
                        = 'false';
                    $validatedProducts['configurable'][$parentId][$product->getId()]['category']
                        = $profile->getData('betterthat_categories');
                    $validatedProducts['configurable'][$parentId][$product->getId()]['profile_id']
                        = $profile->getId();
                    $validatedProducts['configurable'][$parentId][$product->getId()]['upload_as_simple']
                        = ($uploadConfigAsSimple == true) ? 'true' : 'false';
                    if ($key == 0) {
                        $validatedProducts['configurable'][$parentId][$product->getId()]['isprimary']
                            = 'true';
                        $key = 1;
                    }
                    $product->setData('betterthat_validation_errors', '["valid"]');
                    $product->getResource()
                        ->saveAttribute($product, 'betterthat_validation_errors');
                    continue;
                } else {
                    $errorIndex = implode(", ", $unmappedVariantAttribute);
                    $errors[$product->getSku()]['errors'][][$errorIndex] = [
                        'Configurable attributes not mapped.'];
                }
            } elseif (isset($productId['errors'])) {
                $errors[$product->getSku()]['errors'][]
                    = $productId['errors'];
                $childError = [];

                if (isset($productId['errors']['sku'])
                    && isset($productId['errors']['errors'])) {
                    $childError[$productId['errors']['sku']]['errors'][]
                        = $productId['errors']['errors'];
                    $product
                        ->setbetterthat_validation_errors($this->json->jsonEncode($childError));
                    $product
                        ->getResource()
                        ->saveAttribute($product, 'betterthat_validation_errors');
                }
            }
        }
        if (!empty($errors)) {
            $errorsInRegistry =
                $this->registry
                    ->registry('betterthat_product_validaton_errors');
            $this->registry
                ->unregister('betterthat_product_validaton_errors');
            $this->registry
                ->register(
                    'betterthat_product_validaton_errors',
                    is_array($errorsInRegistry) ?
                        $this->arrMerge($errorsInRegistry, $errors)
                        : $errors
                );
            $configurableProduct
                ->setbetterthat_validation_errors($this->json->jsonEncode($errors));
            $configurableProduct
                ->getResource()
                ->saveAttribute($configurableProduct, 'betterthat_validation_errors');
        } else {
            $configurableProduct->setbetterthat_validation_errors('["valid"]');
            $configurableProduct->getResource()
                ->saveAttribute($configurableProduct, 'betterthat_validation_errors');
        }
        return $validatedProducts;
    }

    /**
     * ArrMerge
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public function arrMerge($array1, $array2)
    {
        return array_merge($array1, $array2);
    }

    /**
     * Validate product for availability of required Betterthat product attribute data
     *
     * @param string $id
     * @param mixed $product
     * @param mixed $profile
     * @param mixed $parentId
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
            if ($product->getBetterthatVisibility() == 0
                && !$parentId) {
                return [
                    "bt_visibility" =>
                        "Item's visibility is not visible hence can't be uploaded"
                ];
            }
            //if profile is not passed, get profile
            if ($profile == null) {
                $profile = $this->profileHelper
                    ->getProfile($product->getId());
            }
            $profileId = $profile->getId();
            $sku = $product->getSku();
            //Case 1: Profile is Available
            if (isset($profileId) && $profileId != false) {
                unset($validatedProduct);
                $validatedProduct = [];
                $category = $profile->getBetterThatCategory();
                $errors = $this->innerValidateItems($profile, $product, $category);
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
                    $product
                        ->setbetterthat_validation_errors($this->json->jsonEncode($e));
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
                //Case 2: Profile is not available, not needed case
                $errors = [
                    "sku" => "$sku",
                    "id" => "$id",
                    "url" => $this->urlBuilder
                        ->getUrl(
                            'catalog/product/edit',
                            ['id' => $product->getId()]
                        ),
                    "errors" =>
                        [
                            "Profile not found" =>
                                "Product or Parent Product is not
                                mapped in any Betterthat profile"
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
            $this->logger->error(
                'Validate Product',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * InnerValidateItems
     *
     * @param mixed $profile
     * @param mixed $product
     * @param mixed $category
     * @return array
     */
    public function innerValidateItems($profile, $product, $category)
    {
        $errors = [];
        $productArray = $product->toArray();
        $requiredAttributes = $profile->getRequiredAttributes();
        foreach ($requiredAttributes as $BetterthatAttributeId => $BetterthatAttribute) {
            $skippedAttribute = ['short_description'];
            if (isset($BetterthatAttribute['default'])
                && !empty($BetterthatAttribute['default'])
            ) {
                if ($BetterthatAttribute['default']) {
                    continue;
                }
            }
            if (in_array($BetterthatAttributeId, $skippedAttribute)) {
                // Validation case 1 skip some attributes that are not to be validated.
                continue;
            } elseif ((!isset($productArray[$BetterthatAttribute['magento_attribute_code']])
                || empty($productArray[$BetterthatAttribute['magento_attribute_code']]) )
                && empty($BetterthatAttribute['default'])
            ) {
                // Validation case 2 Empty or blank value check
                $errors["$BetterthatAttributeId"] =
                    "Required attribute empty or not mapped. [
                            {$BetterthatAttribute['magento_attribute_code']}]";
            } elseif (isset($BetterthatAttribute['options'])
                && !empty($BetterthatAttribute['options']
                    || !empty($BetterthatAttribute['default']))
            ) {
                $valueId = $product
                    ->getData($BetterthatAttribute['magento_attribute_code']);
                $value = "";
                $defaultValue = "";
                // Case 2: default value from profile
                if (isset($BetterthatAttribute['default'])
                    && !empty($BetterthatAttribute['default'])
                ) {
                    $defaultValue = $BetterthatAttribute['default'];
                    if ($defaultValue) {
                        continue;
                    }
                }
                // Case 3: magento attribute option value
                $attr = $product->getResource()
                    ->getAttribute($BetterthatAttribute['magento_attribute_code']);
                if ($attr
                    && ($attr->usesSource()
                        || $attr->getData('frontend_input') == 'select')) {
                    $value = $attr->getSource()
                        ->getOptionText($valueId);
                    if (is_object($value)) {
                        $value = $value->getText();
                    }
                }
                // order of check: default value > option mapping > default magento option value
                if (!isset($BetterthatAttribute['options'][$defaultValue])
                    && !isset($BetterthatAttribute['option_mapping'][$valueId])
                    && !isset($BetterthatAttribute['options'][$value])
                ) {
                    $errors["$BetterthatAttributeId"]
                        = "Betterthat attribute: [" . $BetterthatAttribute['name'] .
                        "] mapped with [" . $BetterthatAttribute['magento_attribute_code'] .
                        "] has invalid option value: <b> " . json_encode($value) . "/" . json_encode($valueId) .
                        "</b> or default value: " . json_encode($defaultValue);
                }
            }
        }
        $image = $product->getImage();
        if (!$image || $image == 'no_selection') {
            $errors['Image'] = 'Product should have Images';
        }
        return $errors;
    }

    /**
     * PrepareSimpleProducts
     *
     * @param array $ids
     * @return false|void
     */
    private function prepareSimpleProducts($ids = [])
    {
        try {
            $product_array = [];
            $retailer_id = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/retailer_id");
            foreach ($ids as $key => $id) {
                $product = $this->product->create()
                    ->load($id['id']);
                $profile = $this->profileHelper
                    ->getProfile($product->getId(), $id['profile_id']);
                $categories = json_decode($id['category'], 1);
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
                $images = $this->prepareImages($product, false);
                $product_array = [
                    "id" => isset($id['id']) ? $id['id'] : '',
                    "title" => isset($attributes['title']) ? $attributes['title'] : '',
                    "visibility" => $product->getBetterthatVisibility() ? true : false,
                    "body_html" => isset($attributes['body_html'])
                        ? $attributes['body_html'] : '',
                    "short_description" => isset($attributes['short_description'])
                        ? $attributes['short_description'] : '',
                    "retailer_id" => $retailer_id,
                    "dimensions" => [
                        "length" => 0,
                        "height" => 0,
                        "width" => 0,
                        "weight" => $product->getWeight()
                            ? $product->getWeight() : 0,
                        "weight_unit" => "lb"
                    ],
                    "product_categories" => $categories,
                    "can_be_bundled" => isset($attributes['can_be_bundled'])
                        ? $attributes['can_be_bundled'] : '',
                    "manufacturer" => isset($attributes['manufacturer'])
                        ? $attributes['manufacturer'] : '',
                    "policy_description_option" => "",
                    "policy_description_val" => "",
                    "product_shipping_options" => [isset($attributes['product_shipping_options'])
                        ? $attributes['product_shipping_options'] : ''],
                    "shipping_option_charges" => [isset($attributes['shipping_option_charges'])
                        ? $attributes['shipping_option_charges'] : ''],
                    "standard_delivery_timeframe" => isset($attributes['standard_delivery_timeframe'])
                        ? $attributes['standard_delivery_timeframe'] : '',
                    "product_return_window" => isset($attributes['product_return_window'])
                        ? $attributes['product_return_window'] : '',
                    "variants" => [
                        [
                            "id" => isset($id['id']) ? $id['id'] : '',
                            "product_id" => isset($id['id']) ? $id['id'] : '',
                            "title" => "Default Title",
                            "price" => isset($price['price']) ? $price['price'] : '',
                            "discounted_price" => isset($price['special_price'])
                                ? $price['special_price'] : '',
                            "sku" => $product->getSku(),
                            "position" => 1,
                            "inventory_policy" => "deny",
                            "compare_at_price" => isset($price['special_price'])
                                ? $price['special_price'] : '',
                            "option1" => "Default Title",
                            "option2" => null,
                            "option3" => null,
                            "inventory_quantity" => $qty
                        ]
                    ],
                    "options" => [
                        [
                            "id" => isset($id['id']) ? $id['id'] : '',
                            "product_id" => isset($id['id']) ? $id['id'] : '',
                            "name" => "Title",
                            "position" => 1,
                            "values" => [
                                "Default Title"
                            ]
                        ]
                    ],
                    "images" => $images,
                    "image" => isset($images[1]) ? $images[1] : [],
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
            $this->logger->error(
                'Create Product',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    //@TODO ADD input type check

    /**
     * Prepare Attributes for Products
     *
     * @param mixed $product
     * @param mixed $profile
     * @param mixed $type
     * @return array
     */
    private function prepareAttributes($product, $profile = null, $type = "normal")
    {
        try {
            $data = [];
            $mapping = $profile->getAttributes($type);
            if (!empty($mapping)) {
                $data = $this->innerPrepareAttributes($mapping, $product);
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
            $this->logger->error(
                'Validate/Create Product',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * InnerPrepareAttributes
     *
     * @param mixed $mapping
     * @param mixed $product
     * @return array
     */
    public function innerPrepareAttributes($mapping, $product)
    {
        foreach ($mapping as $id => $attribute) {
            $productAttributeValue = "";
            // case 1: default value
            if (isset($attribute['default'])
                && !empty($attribute['default'])
                && $attribute['magento_attribute_code'] == 'default'
            ) {
                $productAttributeValue = str_replace(
                    "&#39;",
                    "'",
                    $attribute['default']
                );
            } else {
                // case 2: Options
                // case 2.1: Option mapping value
                $value = $product->getData($attribute['magento_attribute_code']);
                $attr = $product->getResource()->getAttribute(
                    $attribute['magento_attribute_code']
                );

                if (isset($attribute['option_mapping'][$value])
                    && is_array($attribute['option_mapping'])
                ) {
                    $productAttributeValue =
                        str_replace("&#39;", "'", $attribute['option_mapping'][$value]);
                } elseif ($attr &&
                    ($attr->usesSource()
                        || $attr->getData('frontend_input') == 'select')
                ) {
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
        return $data;
    }

    /**
     * GetPrice
     *
     * @param mixed $productObject
     * @param mixed $attrValue
     * @return string[]
     */
    public function getPrice($productObject, $attrValue = null)
    {
        $splprice = (float)$productObject->getspecial_price();
        $price = (float)$productObject->getPrice();
        if ($attrValue != '') {
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
     *
     * @param mixed $price
     * @param mixed $fixedPrice
     * @param mixed $configPrice
     * @return float|null
     */
    public function forFixPrice($price = null, $fixedPrice = null, $configPrice = null)
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
     *
     * @param mixed $price
     * @param mixed $percentPrice
     * @param mixed $configPrice
     * @return float|null
     */
    public function forPerPrice($price = null, $percentPrice = null, $configPrice = null)
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
     *
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
                            //list($prodWidth, $prodHeight) = getimagesize($image->getUrl());
                            //if ($prodWidth > 450 && $prodHeight > 367) {
                            $this->images[] =
                                [
                                    "id" => $product->getId(),
                                    "product_id" => $product->getId(),
                                    "position" => $image_index,
                                    "alt" => null,
                                    "src" => $image->getUrl(),
                                    "variant_ids" => [(int)$product->getId()]
                                ];
                            $image_index++;
                            //}

                    }
                }
            }
            return $this->images;
        } catch (\Exception $e) {
            $this->logger->error(
                'Validate/Create Product Images Prepare',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * PrepareImages
     *
     * @param mixed $product
     * @param bool $config
     * @return array|false
     */
    private function prepareImages($product, $config = false)
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
                        if ($config) {
                            $this->images[] =
                                [
                                    "id" => $product->getId(),
                                    "product_id" => $product->getId(),
                                    "position" => $image_index,
                                    "alt" => null,
                                    "src" => $image->getUrl(),
                                    "variant_ids" => []
                                ];
                        } else {
                            $images[] =
                                [
                                    "id" => $product->getId(),
                                    "product_id" => $product->getId(),
                                    "position" => $image_index,
                                    "alt" => null,
                                    "src" => $image->getUrl(),
                                    "variant_ids" => [(int)$product->getId()]
                                ];
                        }
                            $image_index++;
                    }
                }
            }
            return $images;
        } catch (\Exception $e) {
            $this->logger->error(
                'Validate/Create Product Images Prepare',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * PrepareConfigurableProducts
     *
     * @param array $ids
     * @return array|false
     */
    private function prepareConfigurableProducts($ids = [])
    {
        try {
            $retailer_id = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/retailer_id");
            foreach ($ids as $parentId => $id) {
                $product = $this->product->create()->load($parentId);
                $profile = $this->profileHelper
                    ->getProfile($product->getId(), $product->getbetterthat_profile_id());
                $categories = json_decode($profile->getBetterThatCategory(), 1);
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
                $this->prepareImages($product, true);
                $collectVariant = $this
                    ->prepareVariants($ids[$parentId], $profile, $variantAttributes, $parentId);
                $this->data = [
                    "id" => $parentId,
                    "title" => isset($attributes['title']) ? $attributes['title'] : '',
                    "body_html" => isset($attributes['body_html']) ? $attributes['body_html'] : '',
                    "short_description" => isset($attributes['short_description'])
                        ? $attributes['short_description'] : '',
                    "retailer_id" => $retailer_id,
                    "visibility" => $product->getBetterthatVisibility() ? true : false,
                    "dimensions" => [
                        "length" => 0,
                        "height" => 0,
                        "width" => 0,
                        "weight" => $product->getWeight() ? $product->getWeight() : 0,
                        "weight_unit" => "lb"
                    ],
                    "product_categories" => $categories,
                    "slug" => isset($attributes['slug']) ? $attributes['slug'] : '',
                    "can_be_bundled" => isset($attributes['can_be_bundled'])
                        ? $attributes['can_be_bundled'] : '',
                    "manufacturer" => isset($attributes['manufacturer'])
                        ? $attributes['manufacturer'] : '',
                    "policy_description_option" => "",
                    "policy_description_val" => "",
                    "product_shipping_options" => [isset($attributes['product_shipping_options'])
                        ? $attributes['product_shipping_options'] : ''],
                    "shipping_option_charges" => [isset($attributes['shipping_option_charges'])
                        ? $attributes['shipping_option_charges'] : ''],
                    "standard_delivery_timeframe" => isset($attributes['standard_delivery_timeframe'])
                        ? $attributes['standard_delivery_timeframe'] : '',
                    "product_return_window" => isset($attributes['product_return_window'])
                        ? $attributes['product_return_window'] : '',
                    "variants" => isset($collectVariant['variant'])
                        ? $collectVariant['variant'] : '',
                    "options" => isset($collectVariant['variantOptions'])
                        ? $collectVariant['variantOptions'] : '',
                    "images" => $this->images,
                    "image" => isset($this->images[0])
                        ? $this->images[0] : [],
                ];
            }
            return $this->data;
        } catch (\Exception $e) {
            $this->logger->error(
                'Create Configurable Product',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * PrepareVariants
     *
     * @param array $ids
     * @param mixed $profile
     * @param mixed $variantAttributes
     * @param string $parentId
     * @return array
     */
    public function prepareVariants($ids = [], $profile = null, $variantAttributes = null, $parentId = null): array
    {
        $pos = 1;
        $varindex = 0;
        $variant = [];
        $optionsPreparation = [];
        $optionflag = false;
        foreach ($ids as $key => $id) {
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
                "discounted_price" => $childproduct->getSpecialPrice(),
                "sku" => $childproduct->getSku(),
                "position" => $pos,
                "inventory_policy" => "deny",
                "compare_at_price" => $childproduct->getFinalPrice(),
                "inventory_quantity" => $qty
            ];
            $optionIndex = 1;
            foreach ($variantAttributes as $variantAttribute) {
                $variant[$varindex]['option' . $optionIndex]
                    = $childproduct->getAttributeText($variantAttribute['attribute_code']);
                $filteredOptions = array_column($variantAttribute['options'], 'label');
                if (!$optionflag) {
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
     *
     * @param array $responses
     * @return boolean
     */
    public function saveResponse($responses = [])
    {
        $this->registry->unregister('Betterthat_product_errors');
        if (is_array($responses)) {
            try {
                foreach ($responses as $response) {
                    $this->registry->unregister('Betterthat_product_errors');
                    $this->registry->register('Betterthat_product_errors', $response);
                    $feedModel = $this->feeds->create();
                    $feedModel->addData(
                        [
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
                        ]
                    );
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
                $this->logger->error(
                    'Save Product/Offer Response',
                    [
                        'path' => __METHOD__,
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]
                );
            }
        }
        return false;
    }

    /**
     * DeleteProducts
     *
     * @param array $ids
     * @return array
     */
    public function deleteProducts($ids = [])
    {
        try {
            $deletedIds = [];
            foreach ($ids as $id) {
                $response = $this->Betterthat
                    ->create(['config' => $this->config->getApiConfig()])
                    ->deleteProduct($id, ["product_id" => $id]);
                if (isset($response['status']) && $response['status']) {
                    $deletedIds[] = $id;
                }
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $deletedIds;
    }

    /**
     * UpdatePriceInventory
     *
     * @param array $ids
     * @param array $withProducts
     * @param array $makeInactive
     * @return false|string
     */
    public function updatePriceInventory($ids = [], $withProducts = false, $makeInactive = false)
    {
        try {
            $index = 0;
            $response = false;
            if (!empty($ids)) {
                $this->ids = [];
                $threshold_status = $this->config->getThresholdStatus();
                $threshold_limit = $this->config->getThresholdLimit();
                $threshold_min = $this->config->getThresholdLimitMin();
                $threshold_max = $this->config->getThresholdLimitMax();
                foreach ($ids as $origIndex => $id) {
                    $product = $this->product->create()->load($id);
                    // configurable Product
                    if ($product->getTypeId() == 'configurable') {
                        $this->ids[] = $product->getId();
                        $configurableProduct = $product;
                        $productType = $configurableProduct->getTypeInstance();
                        $products = $productType->getUsedProducts($configurableProduct);
                        $cindex = $index;
                        $configId = $configurableProduct->getId();
                        foreach ($products as $product) {
                            $product = $this->product->create()->load($product->getId());
                            $price = $this->getPrice($product);
                            $product_id = $product->getId();
                            $quantity = $this->getFinalQuantityToUpload($product);
                            $stock[] =
                                [
                                    "variant_id" => $product_id,
                                    "stock" => $quantity,
                                    "buy_price" => isset($price['price']) ? $price['price'] : '',
                                    "discounted_price" => isset($price['special_price'])
                                        ? $price['special_price'] : ''
                                ];

                            $cindex++;

                        }
                        $invupdate = [
                            "products" => [
                                [
                                    "product_id" => $configId,
                                    "stocks" => $stock
                                ]
                            ]
                        ];

                        $response = $this->Betterthat
                            ->create(
                                [
                                    'config' => $this->config->getApiConfig()
                                ]
                            )->updateInventory($invupdate);
                        $index = $cindex;
                    } elseif ($product->getTypeId() == 'simple') {
                        $this->ids[] = $product->getId();
                        $price = $this->getPrice($product);
                        $quantity = $this->getFinalQuantityToUpload($product);
                        //override product_id
                        $product_id = $product->getId();
                        $invupdate = [
                            "products" => [
                                [
                                    "product_id" => $product_id,
                                    "stocks" => [
                                        [
                                            "variant_id" => $product_id,
                                            "stock" => (int)$quantity,
                                            "buy_price" => (float)isset($price['price'])
                                                ? $price['price'] : '',
                                            "discounted_price" => (float)isset($price['special_price'])
                                                ? $price['special_price'] : ''
                                        ]
                                    ]
                                ]
                            ]
                        ];

                        $response = $this->Betterthat
                            ->create(['config' => $this->config->getApiConfig()])
                            ->updateInventory($invupdate);
                    }
                    $this->registry->register('changed_product_id', $product->getId());
                    $index++;
                }
            }
            return $response;
        } catch (\Exception $e) {
            $this->logger->error(
                'Offer Update',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
        return $response;
    }
    /**
     * Update Product Status
     *
     * @param array $ids
     * @param string $status
     * @return bool
     */
    public function updateStatus($ids = [], $status = \Betterthat\Betterthat\Model\Source\Product\Status::UPLOADED)
    {
        $betterthatId = null;
        if (!empty($ids) && is_array($ids)
            && in_array($status, \Betterthat\Betterthat\Model\Source\Product\Status::STATUS)
        ) {
            if (isset($this->response['_id'])) {
                $betterthatId =
                    $this->response['_id'] . ':'
                    . $this->response['retailer_products'][0]['_id']
                    . ':' . $this->response['variants'][0]['_id'];
            }
            try {
                foreach ($ids as $index => $product) {
                    $product = $this->product->create()->load($product);
                    $product->setData('betterthat_product_status', $status);
                    $product->getResource()->saveAttribute($product, 'betterthat_product_status');
                    if ($betterthatId) {
                        $product->setData('betterthat_product_id', $betterthatId);
                        $product->getResource()->saveAttribute($product, 'betterthat_product_id');
                        $product->setData('betterthat_feed_errors', json_encode($this->serverResponse));
                        $product->getResource()->saveAttribute($product, 'betterthat_feed_errors');
                    } else {
                        $product->setData('betterthat_feed_errors', json_encode($this->serverResponse));
                        $product->getResource()->saveAttribute($product, 'betterthat_feed_errors');
                    }
                }
                return true;
            } catch (\Exception $e) {
                $e->getMessage();
            }
        }
        return false;
    }

    /**
     * Check if configurations are valid
     *
     * @return boolean
     */
    public function checkForConfiguration()
    {
        return $this->config->isValid();
    }

    /**
     * GetProductReference
     *
     * @param string $type
     * @param string $product
     * @return bool|mixed|string
     */
    public function getProductReference($type = '', $product = '')
    {
        if ($type == 'type') {
            $type = $this->config->getReferenceType();
            return $type;
        }
        if ($type == 'value') {
            $type = $this->config->getReferenceValue();
            if ($type) {
                $attr = $product->getResource()->getAttribute($type);
                $refType = $this->config->getReferenceType();
                if ($attr) {
                    if ($attr->getSourceModel($attr)
                        || $attr->getData('frontend_input') == 'select'
                    ) {
                        $valueId = $product->getData($type);
                        $value = $attr->getSource()->getOptionText($valueId);
                        $result = $this->validateProductId($value, $refType);
                        if ($result) {
                            return $value;
                        }
                        return $result;
                    } else {
                        $value = $product->getData($type);
                        $result = $this->validateProductId($value, $refType);
                        if ($result) {
                            return $value;
                        }
                        return $result;
                    }
                } else {
                    $value = $product->getData($type);
                    $result = $this->validateProductId($value, $refType);
                    if ($result) {
                        return $value;
                    }
                    return $result;
                }
            }
        }
        return false;
    }

    /**
     * ValidateProductId
     *
     * @param  string $productID
     * @param  string $barcodeType
     * @return bool
     */
    public function validateProductId($productID, $barcodeType)
    {
        if (!in_array($barcodeType, ['upc', 'ean', 'isbn', 'mpn'])) {
            return false;
        }
        if ($barcodeType == 'mpn') {
            return true;
        }
        if (preg_match('/[^0-9]/', $productID)) {
            // is not numeric
            return false;
        }
        // pad with zeros to lengthen to 14 digits
        switch (strlen($productID)) {
            case 8:
                $productID = "000000" . $productID;
                break;
            case 12:
                $productID = "00" . $productID;
                break;
            case 13:
                $productID = "0" . $productID;
                break;
            case 14:
                break;
            default:
                // wrong number of digits
                return false;
        }
        // calculate check digit
        $a = [];
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
        $sum = $a[0] + $a[1] + $a[2] + $a[3]
            + $a[4] + $a[5] + $a[6] + $a[7] + $a[8]
            + $a[9] + $a[10] + $a[11] + $a[12];
        $check = (10 - ($sum % 10)) % 10;
        // evaluate check digit
        $last = (int)($productID[13]);
        return $check == $last;
    }
    /**
     * Create/Update Product on Betterthat
     *
     * @param  [] $ids
     * @return bool
     */
    public function prepareProductData($ids = [])
    {
        try {
            $response = false;
            $ids = $this->validateAllProducts($ids);
            if (isset($ids['bt_visibility'])) {
                return $ids;
            }
            if (!empty($ids['simple']) || !empty($ids['configurable'])) {
                $this->ids = [];
                $this->key = 0;
                $this->data = [];
                $this->prepareSimpleProducts($ids['simple']);
                $this->prepareConfigurableProducts($ids['configurable']);
            }
            return $response;
        } catch (\Exception $e) {
            $this->logger->error(
                'Prepare Offer Product Data',
                [
                    'path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return false;
        }
    }

    /**
     * GetFinalQuantityToUpload
     *
     * @param mixed $product
     * @return int|mixed|string
     */
    public function getFinalQuantityToUpload($product)
    {
        $quantity = 0;
        $useMSI = $this->config->getUseMsi();
        if ($useMSI) {
            $useSalableQty = $this->config->getUseSalableQty();
            if ($useSalableQty) {
                $msiStockName = $this->config->getSalableStockName();
                $getSalableQuantityDataBySku = $this->objectManager
                    ->create(\Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku::class);
                $invSourceData = $getSalableQuantityDataBySku->execute($product->getSku());
                if ($invSourceData && is_array($invSourceData) && count($invSourceData) > 0) {
                    $invSourceData = array_column($invSourceData, 'qty', 'stock_name');
                    $quantity = isset($invSourceData[$msiStockName]) ? $invSourceData[$msiStockName] : 0;
                } else {
                    $quantity = 0;
                }
            } else {
                $msiSourceCode = $this->config->getMsiSourceCode();
                $msiSourceDataModel = $this->objectManager
                    ->create(\Magento\InventoryCatalogAdminUi\Model\GetSourceItemsDataBySku::class);
                $invSourceData = $msiSourceDataModel->execute($product->getSku());
                if ($invSourceData && is_array($invSourceData) && count($invSourceData) > 0) {
                    $invSourceData = array_column($invSourceData, 'quantity', 'source_code');
                    $quantity = isset($invSourceData[$msiSourceCode]) ? $invSourceData[$msiSourceCode] : 0;
                } else {
                    $quantity = 0;
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
