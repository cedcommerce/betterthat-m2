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
 * @package     Betterthat-Sdk
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

namespace BetterthatSdk\Core;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

abstract class Request implements \BetterthatSdk\Core\RequestInterface
{
    /**
     * Debug Logging
     * @var $debugMode
     */
    public $debugMode;

    /**
     * Logger
     * @var $logger
     */
    public $logger;

    /**
     * Api Base Url
     * @var string $apiUrl
     */
    public $apiUrl;

    /**
     * Api Auth Key
     * @var string $apiAuthKey
     */
    public $apiAuthKey;

    /**
     * XML Parser
     * @var \BetterthatSdk\Core\Generator
     */
    public $xml;

    /**
     * Parser
     * @var \BetterthatSdk\Core\Parser
     */
    public $parser;

    /**
     * Base Directory
     * @var string
     */
    public $baseDirectory;

    /**
     * Xsd files path
     * @var string
     */
    public $xsdPath;

    /**
     * Xsd files directory
     * @var string
     */
    public $xsdDir;

    public $clientId;

    public $clientSecret;

    public $client_domain;


        /**
     * Request constructor.
     * @param ConfigInterface $config
     */
    public function __construct(\BetterthatSdk\Core\ConfigInterface $config)
    {
        $this->debugMode = $config->getDebugMode();
        $this->xml = $config->getGenerator();
        $this->parser = $config->getParser();
        $this->logger = $config->getLogger();
        $this->clientId = $config->getClientId();
        $this->clientSecret = $config->getClientSecret();
        $this->apiUrl = $config->getApiUrl();
        $this->client_domain =                                           $config->getClientDomain();

    }

   /**
     * Post Request
     * $params = ['file' => "", 'data' => "" ]
     * @param string $url
     * @param array $params
     * @return string
     */
    public function postRequest($url, $params = array(), $uploadType = NULL)
    {
        $request = null;
        $response = null;
        try {
            $body = '';
            if (isset($params['data'])) {
                $body = json_encode($params['data']);
            }
            $url= $this->apiUrl.$url;
            $headers = [
                'clientId: '. $this->clientId,
                'clientSecret: '. $this->clientSecret,
                'Content-Type: application/json',
                'origin: '.$this->client_domain
            ];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => $headers,
            ]);
            $response = curl_exec($curl);
            //$response = '{"message":"Product imported successfully!","data":{"_id":"6135da610d949ba09c34dac8","product_name":"Aurora - Cuddly Friends - 12\" Panda,White/Black","title":"Aurora - Cuddly Friends - 12\" Panda,White/Black","prod_details":"12 inches long.  High quality materials make for a soft touch!  Package Weight: 0.181 kilograms.  This Panda comes with soft black and white fur at a great value.","dimensions":{"length":25.3,"width":33.5,"height":4.5,"weight":1,"weight_unit":"kg","dimensions_value":""},"rrp":7.88,"product_id":["8518"],"sku_code":[],"barcode":[],"image_style_rule":"contain","status":true,"external_source":"magento","external_product_id":"8518","Tags":[],"imported_product_name":"Aurora - Cuddly Friends - 12\" Panda,White/Black","can_be_bundled":"No","categories":[{"_id":"5d5fbe24e6802f178f97403e","path":"Kids","slug":"kids","selected":true},{"_id":"5e1e844fdbf19077b5cfbb2e","path":"Kids/Teens","slug":"kids-teens","selected":true},{"_id":"5e1e841bdbf19077b5cfbb2c","path":"Kids/Baby","slug":"kids-baby","selected":false},{"_id":"5f90f0ef18eb7f336d0f611d","path":"Kids/Baby/Sleeping","slug":"kids-baby-sleeping","selected":false},{"_id":"603ec9f14777bb73e3adfc6f","path":"Kids/Baby/Sleeping/Baby Blankets","slug":"kids-baby-sleeping-baby-blankets","selected":true}],"manufacturer":{},"options":[{"id":"8518","product_id":"8518","name":"Title","position":1,"values":["Aurora - Cuddly Friends - 12\" Panda,White/Black"]}],"attributes":[{"_id":"6044736588e4572ec77fcf34","values":["6135d5720d949ba09c34dac2"]}],"images":[{"id":"8518","product_id":"8518","position":1,"alt":null,"src":"http://127.0.0.1/media/catalog/product/s/c/screenshot_from_2020-12-30_02-23-16.png","variant_ids":[]}],"image":[],"variants":[{"_id":"6135da610d949ba09c34dac9","attributes":[{"_id":"6044736588e4572ec77fcf34","name":"Title","val_id":"6135d5720d949ba09c34dac2","val_name":"Aurora - Cuddly Friends - 12\" Panda,White/Black"}],"image_urls":[],"is_available":true,"order":1,"is_base_variance":false,"un_sku_code":[""],"variance_id":"8518","inventory_quantity":"2","price":"7.880000","discounted_price":0}],"retailer_products":[{"_id":"6135da610d949ba09c34dacb","retailer_id":"60f8eb6fb0482c35d61dbeb3","policy_description_option":false,"policy_description_val":"","is_active":true,"is_visible":true,"shipping_option":[],"shipping_option_charges":["1"],"standard_shipping_timeframe":"2 - 3 business days","transaction_charge_option":false,"buy_price":"7.880000","discounted_price":0,"maxReturnDays":"5","good_cause_amount":{"localAmount":0.1,"internationalAmount":0.07},"add_gst_price":0,"stocks":[{"_id":"6135da610d949ba09c34daca","variance_id":"6135da610d949ba09c34dac9","stock":"2","buy_price":"7.880000","discounted_price":0}]}]}}';
            curl_close($curl);
            return $this->isJson($response) ? json_decode($response,1) : $response;
        } catch(\Exception $e) {
            if ($this->debugMode) {
                $this->logger->debug(
                    "BetterthatSdk\\Api\\postRequest() : \n URL: " . $url .
                    "\n Request : \n" . var_export($request, true) .
                    "\n Response : \n " . var_export($response, true) .
                    "\n Errors : \n " . var_export($e->getMessage(), true)
                );
            }
            return false;
        }
    }

    public function isJson($string):string {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

}
