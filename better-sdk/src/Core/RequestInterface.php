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

/**
 * Directory separator shorthand
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * @api
 */
interface RequestInterface
{
    /**
     * Default API URLs
     */
    const Betterthat_API_URL = 'https://api.betterthat.shop:8083/';
    const Betterthat_SANDBOX_API_URL = 'https://api.betterthat.shop:8083/';
    const POST_INVPRICE = 'updateInventoryStockAndPrice/';
    const GET_CATEGORIES_SUB_URL = 'categories/';
    const GET_ORDERS_SUB_URL = 'orders-list';
    const POST_ITEMS_SUB_URL = 'product-upload/';



    const GET_ATTRIBUTES_SUB_URL = 'api/products/attributes';
    const GET_ATTRIBUTES_VALUE_LIST = 'api/values_lists?code=';
    const GET_OFFERS = 'api/offers';

    const GET_ORDERS_DOCUMENT_URL = 'api/orders/documents';
    const GET_ORDERS_DOCUMENT_DOWNLOAD_URL = '/api/orders/documents/download';
    const PUT_SHIPMENT_SUB_URL = 'oms/asn/v7?sellerId=';
    const GET_FEEDS_SUB_URL = 'api/products/imports/%s/error_report';
    const POST_OFFER_IMPORT  = 'api/offers/imports';
    const PUT_INVENTORY_SUB_URL = 'inventory/fbm-lmp/v7?sellerId=';
    const GET_INVENTORY_SUB_URL = 'inventory/v5?itemIds=%s&sellerId=%s';
    const PUT_PRICE_SUB_URL = 'pricing/fbm/v5?sellerId=';
    const PUT_ORDER_ACCEPTANCE = 'api/orders/';
    const PUT_ORDER_REFUND = '/api/orders/refund';
    const GET_SHIPPING_CARRIERS = 'api/shipping/carriers';
    const GET_REASONS = 'api/reasons';
    const PUT_REFUND_URL = 'api/orders/refund';

    const SSL_VERIFY = false;

    const FEED_CODE_ORDER_CREATE = 'order-create';
    const FEED_CODE_ITEM_UPDATE = 'item-update';
    const FEED_CODE_ITEM_DEACTIVATE = 'item-deactivate';
    const FEED_CODE_ITEM_DELETE = 'item-delete';
    const FEED_CODE_INVENTORY_UPDATE = 'inventory-update';
    const FEED_CODE_PRICE_UPDATE = 'price-update';
    const FEED_CODE_ORDER_SHIPMENT = 'order-shipment';
    const FEED_CANCEL_ORDER_ITEM = 'order-accept';


    /**
     * Post Request
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function postRequest($url, $params = []);

}
