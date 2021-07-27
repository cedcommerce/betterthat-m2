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
        $this->apiAuthKey = $config->getApiKey();
        $this->apiUrl = $config->getApiUrl();
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
            $cFile = '';
            if (isset($params['file'])) {
                if (function_exists('curl_file_create')) {
                    $cFile = curl_file_create($params['file']);
                } else {
                    $cFile = '@' . realpath($params['file']);
                }
            } elseif (isset($params['data'])) {
                $body = $params['data'];
            }

            $url= $this->apiUrl.$url;

            if($uploadType == "offer") {
                $withProducts = ($params['with_products'] == 'true') ? 'true' : 'false';
                $body = array('file' => $cFile, 'import_mode' => 'NORMAL', 'with_products' => $withProducts);
            } else {
                $body = array('file' => $cFile);
            }

            $headers = array(
                //'Authorization: ' . $this->apiAuthKey,2021-07-22 11:24:27,424 [  35527]   WARN - .diagnostic.PerformanceWatcher - UI was frozen for 17458ms, details saved to /home/rajneesh/.cache/JetBrains/PhpStorm2020.3/log/threadDumps-freeze-20210722-112414-PS-203.5981.175-ProjectFrameHelper.init-17sec

                'Accept: application/json',
                //'Content-Type: application/json',
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $servererror = curl_error($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            //$header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            if (!empty($servererror)) {
                $request = curl_getinfo($ch);
                curl_close($ch);
                throw new \Exception($servererror);
            }
            curl_close($ch);
            return $body;
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

}
