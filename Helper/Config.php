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
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Helper;

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
     * Object Manager
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
    public $generator;
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

    public function isEnabled()
    {
        $enabled = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_setting/enable');
        return $enabled;
    }

    public function isValid()
    {
        $valid = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_setting/valid');
        return $valid;
    }

    public function validate()
    {
        try {
            $config = $this->getApiConfig();
            $catResponse = $this->objectManager
                ->create('\BetterthatSdk\Product', ['config' => $config])
                ->getCatForValidation(['data' => ['name' => 'test']]);
            if (isset($catResponse['error_key'])) {
                $catResponse['status'] = false;
                return $catResponse;
            } else {
                $catResponse['status'] = true;
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

    public function getOrderSyncCron()
    {
        $orderSyncCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/order_sync_cron');
        return $orderSyncCron;
    }

    public function getOrderCron()
    {
        $orderCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/order_cron');
        return $orderCron;
    }

    public function getOrderShipmentCron()
    {
        $orderShipmentCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/order_shipment_cron');
        return $orderShipmentCron;
    }

    public function getInventoryPriceCron()
    {
        $invCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/inventory_price_cron');
        return $invCron;
    }

    public function getFeedSyncCron()
    {
        $feedCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/feed_sync_cron');
        return $feedCron;
    }

    public function getFullOfferSyncCron()
    {
        $fullOfferCron = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_cron/full_offer_sync_cron');
        return $fullOfferCron;
    }

    public function getRefundOnBetterthat()
    {
        $refundOnBetterthat = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_order/betterthat_refund_from_core');
        return $refundOnBetterthat;
    }

    public function getRefundReason()
    {
        $refundReason = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_order/betterthat_refund_reason');
        return $refundReason;
    }

    public function getCreditMemoOnMagento()
    {
        $creditOnMagento = $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_order/betterthat_creditmemo_on_magento');
        return $creditOnMagento;
    }

    public function getFromParentAttributes()
    {
        $fromParentAttrs = [];
        $parentAttrs = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_use_other_parent"
            );
        if($parentAttrs)
             $fromParentAttrs = explode(',', $parentAttrs);

        return $fromParentAttrs;
    }

    public function getMergeParentImages()
    {
        $mergeImages = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_merge_parent_images"
            );
        return $mergeImages;
    }

    public function getSkipValidationAttributes()
    {
        $skipFromValidation = [];
        $skipAttr = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_skip_from_validation"
            );
        $skipFromValidation = explode(',', $skipAttr);
        return $skipFromValidation;
    }

    public function getConfigAsSimple()
    {
        $uploadAsSimple = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/betterthat_other_prod_setting/betterthat_upload_config_as_simple"
            );
        return $uploadAsSimple;
    }

    public function getOrderIdPrefix()
    {
        $prefix = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/order_id_prefix");
        if (isset($prefix) && !empty($prefix)) {
            return $prefix;
        }
        return '';
    }

    public function getPriceType()
    {
        $priceType = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/price");
        if (isset($priceType) && !empty($priceType)) {
            return $priceType;
        }
        return '0';
    }

    public function getFixedPrice()
    {
        $fixPrice = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/fix_price");
        if (isset($fixPrice) && !empty($fixPrice)) {
            return $fixPrice;
        }
        return '0';
    }

    public function getPercentPrice()
    {
        $percentPrice = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/percentage_price");
        if (isset($percentPrice) && !empty($percentPrice)) {
            return $percentPrice;
        }
        return '0';
    }

    public function getDifferPrice()
    {
        $differPrice = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/price_settings/different_price");
        if (isset($differPrice) && !empty($differPrice)) {
            return $differPrice;
        }
        return '0';
    }

    public function getReferenceType()
    {
        $product_reference_type = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_setting/product_reference_type");
        if (isset($product_reference_type) && !empty($product_reference_type)) {
            return $product_reference_type;
        }
        return '0';
    }

    public function getReferenceValue()
    {
        $product_reference_value = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_setting/product_reference_value");
        if (isset($product_reference_value) && !empty($product_reference_value)) {
            return $product_reference_value;
        }
        return '0';
    }

    public function getAutoCancelOrderSetting()
    {
        $auto_cancel_order = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/auto_cancel_order");
        if (isset($auto_cancel_order) && !empty($auto_cancel_order)) {
            return $auto_cancel_order;
        }
        return '0';
    }

    public function getAutoAcceptOrderSetting()
    {
        $auto_cancel_order = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/auto_accept_order");
        if (isset($auto_cancel_order) && !empty($auto_cancel_order)) {
            return $auto_cancel_order;
        }
        return '0';
    }

    public function getHoldOrderUntilShipping()
    {
        $holdOrder = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_order/hold_order_until_shipping");
        return $holdOrder;
    }

    public function getStore()
    {
        $storeId = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_setting/storeid");
        if (isset($storeId) && !empty($storeId)) {
            return $storeId;
        }
        return '0';
    }

    public function getThrottleMode()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/throttle");
    }

    public function getThresholdStatus()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/advanced_threshold_status");
    }

    public function getThresholdLimit()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/inventory_rule_threshold");
    }

    public function getThresholdLimitMin()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/inventory_settings/send_inventory_for_lesser_than_threshold"
            );
    }

    public function getThresholdLimitMax()
    {
        return $this->throttle = $this->scopeConfigManager
            ->getValue(
                "betterthat_config/betterthat_product/inventory_settings/send_inventory_for_greater_than_threshold"
            );
    }

    public function getUseMsi()
    {
        $useMsi = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/use_msi");
        return $useMsi;
    }

    public function getMsiSourceCode()
    {
        $msiSourceCode = $this->scopeConfigManager
            ->getValue("betterthat_config/betterthat_product/inventory_settings/msi_source_code");
        return $msiSourceCode;
    }

    public function getUseSalableQty()
    {
        return $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_product/inventory_settings/use_salable_qty');
    }

    public function getSalableStockName()
    {
        return $this->scopeConfigManager
            ->getValue('betterthat_config/betterthat_product/inventory_settings/salable_stock_name');
    }
}
