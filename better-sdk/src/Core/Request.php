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
 * @package     Betterthat-Sdk
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
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
        $this->client_domain = $config->getClientDomain();

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
