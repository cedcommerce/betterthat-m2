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

namespace BetterthatSdk;

class Order extends \BetterthatSdk\Core\Request
{

    /**
     * @param string $subUrl
     * @param null $orderId
     * @return false|string
     */
    public function getOrders($subUrl = self::GET_ORDERS_SUB_URL,$orderId=null)
    {
        if($orderId)
        {
            $response = $this->postRequest($subUrl ,
                [
                    'data'=> [
                        'start' => '1',
                        'sort' => '[{\'dir\':asc},{\'column\':\'1\'}]',
                        'searchById' => $orderId
                    ]
                ]);
            return $response;
        }
        $response = $this->postRequest($subUrl ,
            ['data'=> ['start' => '1','sort' => '[{\'dir\':asc},{\'column\':\'1\'}]']] );
        return $response;
    }

    /**
     * @param null $purchaseOrderId
     * @param string $subUrl
     * @return mixed
     */
    public function putShipOrder($purchaseOrderId = null, $subUrl = self::PUT_ORDER_ACCEPTANCE)
    {
        $url = $subUrl . $purchaseOrderId . '/ship';
        $response = $this->putRequest($url);
        return $response;

    }

    /**
     * @param $purchaseOrderId
     * @param $trackingInfo
     * @param string $subUrl
     * @return mixed
     * @throws \DOMException
     */
    public function updateTrackingInfo($purchaseOrderId, $trackingInfo, $subUrl = self::PUT_ORDER_ACCEPTANCE)
    {
        $shippingInfoData = array(
            'tracking' => array(
                '_attribute' => array(),
                '_value' => array(
                    'carrier_code' => isset($trackingInfo['carrier_code']) ? $trackingInfo['carrier_code'] : '',
                    'carrier_name' => isset($trackingInfo['carrier_name']) ? $trackingInfo['carrier_name'] : '',
                    'carrier_url' => isset($trackingInfo['tracking_url']) ? $trackingInfo['tracking_url'] : '',
                    'tracking_number' => isset($trackingInfo['tracking_number']) ? $trackingInfo['tracking_number'] : '',
                )
            )
        );
        $url = $subUrl . $purchaseOrderId . '/tracking';
        $xml = new \BetterthatSdk\Core\Generator();
        $trackingXML = $xml->arrayToXml($shippingInfoData);
        $response = $this->putRequest($url, array('data' => $trackingXML->__toString()));
        return $response;
    }

}
