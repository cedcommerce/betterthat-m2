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

/**
 * Directory separator shorthand
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

use Magento\Framework\App\Helper\Context;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Object ManagerR
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfigManager;

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    public $dl;

    /**
     * @var $userId
     */
    public $userId;

    /**
     * @var $apiKey
     */
    public $apiKey;

    /**
     * @var $endpoint
     */
    public $endpoint;
    /**
     * @var domain
     */
    public $domain;

    /**
     * Debug Log Mode
     *
     * @var boolean
     */
    public $debugMode = true;
    /**
     * @var \BetterthatSdk\Api\ConfigFactory
     */
    public $config;
    /**
     * @var \Magento\Framework\Xml\Generator
     */
    public $generator;
    public $client_id;
    public $client_domain;
    public $client_secret;
    public $throttle;

    /**
     * Config constructor.
     *
     * @param Context $context
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Xml\Generator $generator
     * @param \BetterthatSdk\Api\ConfigFactory $config
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Xml\Generator $generator,
        \BetterthatSdk\Core\ConfigFactory $config
    ) {
        parent::__construct($context);
        $this->scopeConfigManager = $context->getScopeConfig();
        $this->objectManager = $objectManager;
        $this->dl = $directoryList;
        $this->config = $config;
        $this->generator = $generator;
    }

    /**
     * Get default customer id
     *
     * @return bool|string
     */
    public function getDefaultCustomer()
    {
        $customer = false;
        $enabled = $this->scopeConfigManager
            ->getValue('Betterthat_config/Betterthat_order/enable_default_customer');
        if ($enabled == 1) {
            $customer = $this->scopeConfigManager
                ->getValue('Betterthat_config/Betterthat_order/default_customer_email');
        }
        return $customer;
    }

    /**
     * GetApiConfig
     *
     * @return \BetterthatSdk\Api\Config|boolean
     */
    public function getApiConfig()
    {
        $config = false;
        try {
            //loading configurations
            $this->debugMode = $this->scopeConfigManager
                ->getValue('betterthat_config/betterthat_setting/debug_mode');
            $this->userId = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/user_id");
            $this->apiKey = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/api_key");
            $this->endpoint = \BetterthatSdk\Core\Request::Betterthat_API_URL;

            $this->client_id = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/client_id");
            $this->client_secret = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/client_secret");
            $this->client_domain = $this->scopeConfigManager
                ->getValue("betterthat_config/betterthat_setting/client_domain");
            /**
             * @var \BetterthatSdk\Api\Config
             */
            $config = $this->config->create(
                [
                    'params' => [
                        'userId' => $this->userId,
                        'apiKey' => $this->apiKey,
                        'client_id' => $this->client_id,
                        'client_secret' => $this->client_secret,
                        'client_domain' => $this->client_domain,
                        'apiUrl' => $this->endpoint,
                        'debugMode' => $this->debugMode,
                        'baseDirectory' => $this->dl->getPath('var') . DS . 'Betterthat',
                        'generator' => $this->generator,
                    ]
                ]
            );
        } catch (\Exception $exception) {
            $this->_logger->error('Betterthat: ' . $exception->getMessage());
        }

        return $config;
    }

    /**
     * IsEnabled
     *
     * @return mixed
     */
    public function isEnabled()
    {
        $enabled = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_setting/enable');
        return $enabled;
    }

    /**
     * IsValid
     *
     * @return mixed
     */
    public function isValid()
    {
        $valid = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_setting/valid');
        return $valid;
    }

    /**
     * Validate
     *
     * @return void
     */
    public function validate()
    {
        try {
            $config = $this->getApiConfig();
            $catResponse = $this->objectManager
                ->create('\BetterthatSdk\Product', ['config' => $config])
                ->getCatForValidation(['data' => ['name' => 'test']]);

            if (is_array($catResponse) && count($catResponse) > 0 && !isset($catResponse['error_key'])) {
                $catResponse['status'] = true;
                return $catResponse;
            } else {
                $catResponse['status'] = false;
                return $catResponse;
            }
        } catch (\Exception $exception) {
            $this->_logger->error('Betterthat:' . $exception->getMessage());
        }
    }

    /**
     * Get Mock mode for config
     *
     * @return bool
     */
    public function getMode()
    {
        $mode = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_setting/mode');
        return $mode;
    }

    /**
     * GetOrderSyncCron
     *
     * @return mixed
     */
    public function getOrderSyncCron()
    {
        $orderSyncCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/order_sync_cron');
        return $orderSyncCron;
    }

    /**
     * GetOrderCron
     *
     * @return mixed
     */
    public function getOrderCron()
    {
        $orderCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/order_cron');
        return $orderCron;
    }

    /**
     * GetOrderShipmentCron
     *
     * @return mixed
     */
    public function getOrderShipmentCron()
    {
        $orderShipmentCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/order_shipment_cron');
        return $orderShipmentCron;
    }

    /**
     * GetInventoryPriceCron
     *
     * @return mixed
     */
    public function getInventoryPriceCron()
    {
        $invCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/inventory_price_cron');
        return $invCron;
    }

    /**
     * GetFeedSyncCron
     *
     * @return mixed
     */
    public function getFeedSyncCron()
    {
        $feedCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/feed_sync_cron');
        return $feedCron;
    }

    /**
     * GetFullOfferSyncCron
     *
     * @return mixed
     */
    public function getFullOfferSyncCron()
    {
        $fullOfferCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/full_offer_sync_cron');
        return $fullOfferCron;
    }

    /**
     * GetRefundOnBetterthat
     *
     * @return mixed
     */
    public function getRefundOnBetterthat()
    {
        $refundOnBetterthat = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_order/betterthat_refund_from_core');
        return $refundOnBetterthat;
    }

    /**
     * GetRefundReason
     *
     * @return mixed
     */
    public function getRefundReason()
    {
        $refundReason = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_order/betterthat_refund_reason');
        return $refundReason;
    }

    /**
     * GetCreditMemoOnMagento
     *
     * @return mixed
     */
    public function getCreditMemoOnMagento()
    {
        $creditOnMagento = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_order/betterthat_creditmemo_on_magento');
        return $creditOnMagento;
    }

    /**
     * GetFromParentAttributes
     *
     * @return array|string[]
     */
    public function getFromParentAttributes()
    {
        $fromParentAttrs = [];
        $parentAttrs = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_use_other_parent"
            );
        if ($parentAttrs) {
            $fromParentAttrs = explode(',', $parentAttrs);
        }

        return $fromParentAttrs;
    }

    /**
     * GetMergeParentImages
     *
     * @return mixed
     */
    public function getMergeParentImages()
    {
        $mergeImages = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_merge_parent_images"
            );
        return $mergeImages;
    }

    /**
     * GetSkipValidationAttributes
     *
     * @return array|string[]
     */
    public function getSkipValidationAttributes()
    {
        $skipFromValidation = [];
        $skipAttr = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_skip_from_validation"
            );
        if ($skipAttr) {
            $skipFromValidation = explode(',', $skipAttr);
        }
        return $skipFromValidation;
    }

    /**
     * GetConfigAsSimple
     *
     * @return mixed
     */
    public function getConfigAsSimple()
    {
        $uploadAsSimple = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_upload_config_as_simple"
            );
        return $uploadAsSimple;
    }

    /**
     * GetOrderIdPrefix
     *
     * @return mixed|string
     */
    public function getOrderIdPrefix()
    {
        $prefix = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/order_id_prefix");
        if (isset($prefix) && !empty($prefix)) {
            return $prefix;
        }
        return '';
    }

    /**
     * GetPriceType
     *
     * @return mixed|string
     */
    public function getPriceType()
    {
        $priceType = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/price");
        if (isset($priceType) && !empty($priceType)) {
            return $priceType;
        }
        return '0';
    }

    /**
     * GetFixedPrice
     *
     * @return mixed|string
     */
    public function getFixedPrice()
    {
        $fixPrice = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/fix_price");
        if (isset($fixPrice) && !empty($fixPrice)) {
            return $fixPrice;
        }
        return '0';
    }

    /**
     * GetPercentPrice
     *
     * @return mixed|string
     */
    public function getPercentPrice()
    {
        $percentPrice = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/percentage_price");
        if (isset($percentPrice) && !empty($percentPrice)) {
            return $percentPrice;
        }
        return '0';
    }

    /**
     * GetDifferPrice
     *
     * @return mixed|string
     */
    public function getDifferPrice()
    {
        $differPrice = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/different_price");
        if (isset($differPrice) && !empty($differPrice)) {
            return $differPrice;
        }
        return '0';
    }

    /**
     * GetReferenceType
     *
     * @return mixed|string
     */
    public function getReferenceType()
    {
        $product_reference_type = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_setting/product_reference_type");
        if (isset($product_reference_type) && !empty($product_reference_type)) {
            return $product_reference_type;
        }
        return '0';
    }

    /**
     * GetReferenceValue
     *
     * @return mixed|string
     */
    public function getReferenceValue()
    {
        $product_reference_value = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_setting/product_reference_value");
        if (isset($product_reference_value) && !empty($product_reference_value)) {
            return $product_reference_value;
        }
        return '0';
    }

    /**
     * GetAutoCancelOrderSetting
     *
     * @return mixed|string
     */
    public function getAutoCancelOrderSetting()
    {
        $auto_cancel_order = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/auto_cancel_order");
        if (isset($auto_cancel_order) && !empty($auto_cancel_order)) {
            return $auto_cancel_order;
        }
        return '0';
    }

    /**
     * GetAutoAcceptOrderSetting
     *
     * @return mixed|string
     */
    public function getAutoAcceptOrderSetting()
    {
        $auto_cancel_order = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/auto_accept_order");
        if (isset($auto_cancel_order) && !empty($auto_cancel_order)) {
            return $auto_cancel_order;
        }
        return '0';
    }

    /**
     * GetHoldOrderUntilShipping
     *
     * @return mixed
     */
    public function getHoldOrderUntilShipping()
    {
        $holdOrder = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/hold_order_until_shipping");
        return $holdOrder;
    }

    /**
     * GetStore
     *
     * @return mixed|string
     */
    public function getStore()
    {
        $storeId = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_setting/storeid");
        if (isset($storeId) && !empty($storeId)) {
            return $storeId;
        }
        return '0';
    }

    /**
     * GetThrottleMode
     *
     * @return mixed
     */
    public function getThrottleMode()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/throttle");
    }

    /**
     * GetThresholdStatus
     *
     * @return mixed
     */
    public function getThresholdStatus()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/advanced_threshold_status");
    }

    /**
     * GetThresholdLimit
     *
     * @return mixed
     */
    public function getThresholdLimit()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/inventory_rule_threshold");
    }

    /**
     * GetThresholdLimitMin
     *
     * @return mixed
     */
    public function getThresholdLimitMin()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/inventory_settings/send_inventory_for_lesser_than_threshold"
            );
    }

    /**
     * GetThresholdLimitMax
     *
     * @return mixed
     */
    public function getThresholdLimitMax()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/inventory_settings/send_inventory_for_greater_than_threshold"
            );
    }

    /**
     * GetUseMsi
     *
     * @return mixed
     */
    public function getUseMsi()
    {
        $useMsi = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/use_msi");
        return $useMsi;
    }

    /**
     * GetMsiSourceCode
     *
     * @return mixed
     */
    public function getMsiSourceCode()
    {
        $msiSourceCode = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/msi_source_code");
        return $msiSourceCode;
    }

    /**
     * GetUseSalableQty
     *
     * @return mixed
     */
    public function getUseSalableQty()
    {
        return $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_product/inventory_settings/use_salable_qty');
    }

    /**
     * GetSalableStockName
     *
     * @return mixed
     */
    public function getSalableStockName()
    {
        return $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_product/inventory_settings/salable_stock_name');
    }
}
