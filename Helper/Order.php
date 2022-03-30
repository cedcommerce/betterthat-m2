<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Helper;

/**
 * Class Order
 *
 * @package Ced\Betterthat\Helper
 */
class Order extends \Magento\Framework\App\Helper\AbstractHelper
{

    const DEFAULT_EMAIL = 'customer@Betterthat.com';

    /**
     * @var \Magento\Framework\objectManagerInterface
     */
    public $objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    public $customerFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    public $customerRepository;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    public $productRepository;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    public $product;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;

    /**
     * @var \Magento\Sales\Model\Service\OrderService
     */
    public $orderService;

    /** @var \Magento\Quote\Model\Quote\Address\RateFactory */
    public $rateFactory;

    /**
     * @var \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoaderFactory
     */
    public $creditmemoLoaderFactory;

    /**
     * @var \Magento\Quote\Api\CartManagementInterface
     */
    public $cartManagementInterface;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    public $cartRepositoryInterface;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    public $cache;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    public $stockRegistry;

    /**
     * @var \Ced\Betterthat\Model\Orders
     */
    public $orders;

    /**
     * @var \Magento\AdminNotification\Model\Inbox
     */
    public $inbox;

    /**
     * @var
     */
    public $messageManager;

    /**
     * @var \Ced\Betterthat\Model\OrderFailed
     */
    public $orderFailed;

    /**
     * @var \BetterthatSdk\Order
     */
    public $Betterthat;

    /**
     * @var $config
     */
    public $config;

    /**
     * @var Logger
     */
    public $logger;

    /**
     * \
     *
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * Ids of Products
     *
     * @var array $ids
     */
    public $ids = [];

    /**
     * @var \Ced\Betterthat\Model\FeedsFactory
     */
    public $feeds;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $dateTime;

    /**
     * @var \Magento\Sales\Model\Order\AddressRepository
     */
    public $repositoryAddress;

    /**
     * @var \Magento\Sales\Api\Data\OrderInterface
     */
    public $salesOrder;

    /** @var \Ced\Betterthat\Helper\Tax $taxHelper */
    public $taxHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var int
     */
    protected $failedCount;

    /** @var \Magento\Framework\DataObjectFactory */
    public $dataFactory;

    /** @var \Ced\Betterthat\Model\MailFactory */
    public $mailFactory;

    public $changeQuoteControl;
    /**
     * Order constructor.
     *
     * @param \Magento\Framework\App\Helper\Context                             $context
     * @param \Magento\Framework\objectManagerInterface                         $objectManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime                       $dateTime
     * @param \Magento\Store\Model\StoreManagerInterface                        $storeManager
     * @param \Magento\Customer\Model\CustomerFactory                           $customerFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface                 $customerRepository
     * @param \Magento\Catalog\Model\ProductRepository                          $productRepository
     * @param \Magento\Catalog\Model\ProductFactory                             $product
     * @param \Magento\Framework\Json\Helper\Data                               $json
     * @param \Magento\Framework\Registry                                       $registry
     * @param \Magento\Sales\Model\Service\OrderService                         $orderService
     * @param \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoaderFactory $creditmemoLoaderFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface                        $cartRepositoryInterface
     * @param \Magento\Quote\Api\CartManagementInterface                        $cartManagementInterface
     * @param \Magento\Framework\App\Cache\TypeListInterface                    $cache
     * @param \Magento\AdminNotification\Model\Inbox                            $inbox
     * @param \Magento\Framework\Message\ManagerInterface                       $manager
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface              $stockRegistry
     * @param \Ced\Betterthat\Model\OrdersFactory                                   $orders
     * @param \Ced\Betterthat\Model\FeedsFactory                                    $feedsFactory
     * @param \Ced\Betterthat\Model\OrderFailedFactory                              $orderFailed
     * @param Config                                                            $config
     * @param Logger                                                            $logger
     * @param \BetterthatSdk\OrderFactory                                          $Betterthat
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\objectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Framework\Json\Helper\Data $json,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Service\OrderService $orderService,
        \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoaderFactory $creditmemoLoaderFactory,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Magento\Framework\App\Cache\TypeListInterface $cache,
        \Magento\AdminNotification\Model\Inbox $inbox,
        \Magento\Framework\Message\ManagerInterface $manager,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Ced\Betterthat\Model\OrdersFactory $orders,
        \Ced\Betterthat\Model\FeedsFactory $feedsFactory,
        \Ced\Betterthat\Model\OrderFailedFactory $orderFailed,
        \Ced\Betterthat\Helper\Config $config,
        \Ced\Betterthat\Helper\Logger $logger,
        \BetterthatSdk\OrderFactory $Betterthat,
        \Magento\Sales\Model\Order\AddressRepository $repositoryAddress,
        \Magento\Sales\Api\Data\OrderInterface $salesOrderApi,
        \Ced\Betterthat\Helper\Tax $taxHelper,
        \Magento\Quote\Model\Quote\Address\RateFactory $rateFactory,
        \Magento\Framework\DataObjectFactory $dataFactory,
        \Ced\Betterthat\Model\MailFactory $mailFactory,
        \Ced\Betterthat\Model\ChangeQuoteControl $quoteControl
    ) {
        parent::__construct($context);
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->product = $product;
        $this->json = $json;
        $this->orderService = $orderService;
        $this->creditmemoLoaderFactory = $creditmemoLoaderFactory;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->cache = $cache;
        $this->inbox = $inbox;
        $this->messageManager = $manager;
        $this->stockRegistry = $stockRegistry;
        $this->orders = $orders;
        $this->registry = $registry;
        $this->dateTime = $dateTime;
        $this->orderFailed = $orderFailed;
        $this->feeds = $feedsFactory;
        $this->Betterthat = $Betterthat;
        $this->logger = $logger;
        $this->config = $config;
        $this->repositoryAddress= $repositoryAddress;
        $this->salesOrder = $salesOrderApi;
        $this->taxHelper = $taxHelper;
        $this->failedCount = 0;
        $this->rateFactory= $rateFactory;
        $this->mailFactory = $mailFactory;
        $this->dataFactory = $dataFactory;
        $this->changeQuoteControl = $quoteControl;

    }

    /**
     * @return bool
     */
    public function importOrders($data = null)
    {
        try {

            $storeId = $this->config->getStore();
            $store = $this->storeManager->getStore($storeId);
            $websiteId = $store->getWebsiteId();

            $orderList = $this->Betterthat->create(
                [
                    'config' => $this->config->getApiConfig(),
                ]
            );
            if($data)
                $response['data'] = $data;
            else
                $response = $orderList->getOrders();
            //$response = '{"data":[{"_id":"612494f21befab31f4767aa5","is_multi":false,"shipment_response":[{"shipment_id":"5K0K0EuRuO4AAAF7c9odiXbl","shipment_creation_date":"2021-08-24T16:43:00+10:00","items":[{"weight":1,"item_id":"ySEK0EuREpIAAAF7d9odiXbl","item_reference":"SKU-1","tracking_details":{"article_id":"111Z05043035FPP00001","consignment_id":"111Z05043035"},"product_id":"FPP","item_summary":{"status":"Created"},"item_contents":[],"packaging_type":"SAT"}],"options":{},"shipment_summary":{"total_cost":16.85,"total_cost_ex_gst":15.32,"shipping_cost":15.32,"total_gst":1.53,"freight_charge":15.32,"status":"Created","tracking_summary":{"Created":1},"number_of_items":1},"movement_type":"TRANSFER","charge_to_account":"02734739","shipment_modified_date":"2021-08-24T16:43:00+10:00"}],"sendle_label_url":[],"order_status":"inprogress","order_generated":false,"is_cancelled":false,"is_shopify_tracking_status":false,"shopify_order_id":null,"customer_id":"61248ef29b1aed428c6e6945","total_price":28.46,"shipping_price":16.85,"payable_shipping_fee":16.85,"charity_price":0,"referrer_fee":0,"charity_id":"5dc1f96ccbbf5c085defb1bc","shipping_type":"ExpressDelivery","track_url":"","transtation_id":"ch_3JRtLXLRWu5IWm1i0G3H5eGp","stripe_data":"{\"id\":\"ch_3JRtLXLRWu5IWm1i0G3H5eGp\",\"object\":\"charge\",\"amount\":2846,\"amount_captured\":2846,\"amount_refunded\":0,\"application\":null,\"application_fee\":null,\"application_fee_amount\":null,\"balance_transaction\":\"txn_3JRtLXLRWu5IWm1i0WK0qe8T\",\"billing_details\":{\"address\":{\"city\":null,\"country\":null,\"line1\":null,\"line2\":null,\"postal_code\":null,\"state\":null},\"email\":null,\"name\":\"test\",\"phone\":null},\"calculated_statement_descriptor\":\"Stripe\",\"captured\":true,\"created\":1629787375,\"currency\":\"aud\",\"customer\":\"cus_K657qutheJwMHt\",\"description\":\"612494ef1befab31f4767aa4\",\"destination\":null,\"dispute\":null,\"disputed\":false,\"failure_code\":null,\"failure_message\":null,\"fraud_details\":{},\"invoice\":null,\"livemode\":false,\"metadata\":{},\"on_behalf_of\":null,\"order\":null,\"outcome\":{\"network_status\":\"approved_by_network\",\"reason\":null,\"risk_level\":\"normal\",\"risk_score\":34,\"seller_message\":\"Payment complete.\",\"type\":\"authorized\"},\"paid\":true,\"payment_intent\":null,\"payment_method\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"payment_method_details\":{\"card\":{\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":null,\"cvc_check\":null},\"country\":\"US\",\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"installments\":null,\"last4\":\"4242\",\"network\":\"visa\",\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"},\"receipt_email\":null,\"receipt_number\":null,\"receipt_url\":\"https://pay.stripe.com/receipts/acct_1FcUxPLRWu5IWm1i/ch_3JRtLXLRWu5IWm1i0G3H5eGp/rcpt_K65WfyOIxIdXdKObW8OQwWaK8CgF86f\",\"refunded\":false,\"refunds\":{\"object\":\"list\",\"data\":[],\"has_more\":false,\"total_count\":0,\"url\":\"/v1/charges/ch_3JRtLXLRWu5IWm1i0G3H5eGp/refunds\"},\"review\":null,\"shipping\":null,\"source\":{\"id\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"object\":\"card\",\"address_city\":null,\"address_country\":null,\"address_line1\":null,\"address_line1_check\":null,\"address_line2\":null,\"address_state\":null,\"address_zip\":null,\"address_zip_check\":null,\"brand\":\"Visa\",\"country\":\"US\",\"customer\":\"cus_K657qutheJwMHt\",\"cvc_check\":null,\"dynamic_last4\":null,\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"last4\":\"4242\",\"metadata\":{\"futureReference\":\"true\"},\"name\":\"test\",\"tokenization_method\":null},\"source_transfer\":null,\"statement_descriptor\":null,\"statement_descriptor_suffix\":null,\"status\":\"succeeded\",\"transfer_data\":null,\"transfer_group\":null}","stripe_card_data":"VISA 4242","retailer_id":"60f8eb6fb0482c35d61dbeb3","address_id":"61248fb49b1aed428c6e6949","sendle_remittance_amount":16.85,"order_type":"buyit","transaction_fee":1.28,"created_by":"place_order","recipient_name":"test null","master_id":"612494ef1befab31f4767aa4","order_date":"24/08/2021","order_time":"16:42","createdAt":"2021-08-24T06:42:58.763Z","label_url":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/Labels/612494f21befab31f4767aa5/297873828g7af4k2bx.pdf","return_shipment_response":[{"order":{"order_id":"TB01173258","order_creation_date":"2021-08-24T16:43:07+10:00","order_summary":{"total_cost":10.34,"total_cost_ex_gst":9.4,"total_gst":0.94,"status":"Initiated","tracking_summary":{"Sealed":1},"number_of_shipments":1,"number_of_items":1,"dangerous_goods_included":false,"shipping_methods":{"PR":1}},"shipments":[{"shipment_id":"lYgK0EVKCBsAAAF7rvUdbXbl","shipment_creation_date":"2021-08-24T16:43:07+10:00","items":[{"authority_to_leave":true,"safe_drop_enabled":true,"allow_partial_delivery":false,"item_id":"D88K0EVKR4EAAAF7svUdbXbl","item_reference":"SKU-1","tracking_details":{"article_id":"111JD600384001000650802","consignment_id":"111JD6003840"},"product_id":"PR","item_summary":{"total_cost":10.12,"total_cost_ex_gst":9.2,"total_gst":0.92,"status":"Sealed"},"item_contents":[]}],"options":{},"shipment_summary":{"total_cost":10.34,"total_cost_ex_gst":9.4,"fuel_surcharge":0.2,"total_gst":0.94,"status":"Sealed","tracking_summary":{"Sealed":1},"number_of_items":1},"movement_type":"RETURN","charge_to_account":"2012734739","shipment_modified_date":"2021-08-24T16:43:07+10:00"}],"payment_method":"CHARGE_TO_ACCOUNT"}}],"return_label_url":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/ReturnLabels/612494f21befab31f4767aa5/297873906hmvelfsxq.pdf","Order_product":[{"_id":"612494f31befab31f4767aa6","order_id":"612494f21befab31f4767aa5","product_id":"6120e518d65818cad79397e5","price":"11.61","variance_id":"6120e518d65818cad79397e6","quantity":1,"createdAt":"2021-08-24T06:42:59.205Z","updatedAt":"2021-08-24T06:42:59.205Z","__v":0}],"Product_data":[{"_id":"6120e518d65818cad79397e5","percentage_off":0,"strikethrough":11.61,"can_be_bundled":"No","product_id":["8517"],"sku_code":[],"upc_code":[],"ean_code":[],"barcode":[],"gs1_code":[],"gtin_code":[],"size_guide_id":"","Tags":[""],"deleted":false,"domain":"","status":true,"sort_order":393385093,"product_name":"Aurora - Blush and Glitter - 10\" Sweet Pea Mouse","prod_details":"10 inches in size. High quality materials make for a soft and fluffy touch. Quality materials for a soft cuddling experience. Soft and luminous outfit. Plump and stuffed with love","dimensions":{"length":25.3,"weight_unit":"kg","_id":"61248d9bc0406ca6599e33e2","dimension_value":"Extra Small Satchel -500g (275x350x10)","width":33.5,"height":4.5,"weight":1},"rrp":11.61,"image_style_rule":"contain","external_source":"magento","external_product_id":"8517","imported_product_name":"Aurora - Blush and Glitter - 10\" Sweet Pea Mouse","categories":[{"sort_weigth":1000,"_id":"5d5fbe24e6802f178f97403e","path":"Kids","slug":"kids","selected":true},{"sort_weigth":1000,"_id":"603f216dd39af08f527a95c3","path":"Kids/Accessories","slug":"kids-accessories","selected":true},{"sort_weigth":1000,"_id":"5e1e841bdbf19077b5cfbb2c","path":"Kids/Baby","slug":"kids-baby","selected":false},{"sort_weigth":1000,"_id":"5f90f0ef18eb7f336d0f611d","path":"Kids/Baby/Sleeping","slug":"kids-baby-sleeping","selected":false},{"sort_weigth":1000,"_id":"603ec9f14777bb73e3adfc6f","path":"Kids/Baby/Sleeping/Baby Blankets","slug":"kids-baby-sleeping-baby-blankets","selected":true},{"sort_weigth":1000,"_id":"5e1e844fdbf19077b5cfbb2e","path":"Kids/Teens","slug":"kids-teens","selected":true}],"manufacturer":{"isActive":true,"name":"Select Manufacturer"},"attributes":[{"values":["6120e2fbd65818cad79397e2"],"_id":"6044736588e4572ec77fcf34"}],"images":[{"id":null,"_id":"61248d97c0406ca6599e33df","image_order":1,"image_url":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/Products/6120e518d65818cad79397e5/297854957auglbbsi8.jpg"}],"variances":[{"image_urls":[],"is_available":true,"deleted":false,"un_product_id":[],"un_sku_code":[""],"un_gtin_code":[],"un_upc_code":[],"un_ean_code":[],"un_barcode":[],"un_gs1_code":[],"_id":"6120e518d65818cad79397e6","attributes":[{"_id":"6044736588e4572ec77fcf34","name":"Title","val_id":"6120e2fbd65818cad79397e2","val_name":"Aurora - Blush and Glitter - 10\" Sweet Pea Mouse"}],"order":1,"is_base_variance":false,"variance_id":"8517","createdAt":"2021-08-21T11:35:52.681Z","updatedAt":"2021-08-24T06:43:02.249Z"}],"retailer_products":[{"is_active":true,"is_visible":true,"external_status":true,"shipping_option":["Instore","Standard","ExpressDelivery"],"shipping_option_charges":[0,0,10],"transaction_charge_option":false,"buy_price":11.61,"discounted_price":0,"compare_at_price":0,"is_unique":false,"policy_description_option":false,"hideFromFront":false,"_id":"6120e518d65818cad79397ea","retailer_id":"60f8eb6fb0482c35d61dbeb3","transaction_charge_percentage":null,"standard_shipping_timeframe":"3-4 days","policy_description_val":null,"maxReturnDays":5,"international_shipping":{"timeframe":"","charge":0},"stocks":[{"old_stock":4,"discounted_price":0,"compare_at_price":0,"_id":"6120e518d65818cad79397e9","variance_id":"6120e518d65818cad79397e6","stock":3,"buy_price":11.61}]}],"product_tabs":[],"createdAt":"2021-08-21T11:35:52.682Z","updatedAt":"2021-08-24T06:43:02.249Z","slug":"aurora-blush-and-glitter-10-sweet-pea-mouse","__v":0}],"Charity_data":[{"_id":"5dc1f96ccbbf5c085defb1bc","firstName":"Rebecca","lastName":"McCormack","email":"fundraising@guidedogsqld.com.au","registeredCharityName":"Guide Dogs Queensland","registeredAbnName":"GUIDE DOGS FOR THE BLIND ASSOCIATION OF QUEENSLAND","charityAbn":"89009739664","charityPhoneNumber":"0735009088","charityMobileNnumber":"0735009088","charityWebsiteAddress":"https://www.guidedogsqld.com.au/","charityFacebookAddress":"https://www.facebook.com/guidedogsqld/","charityLogo":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/charityLogo/2262803551830yq2ks.jpg","bannerImage":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/bannerImage/979018275m8e8t5v8z.jpg","charityAddress":"1978 Gympie Rd","charitySuburb":"Bald Hills","charityCountry":13,"charityState":269,"charityPostcode":"4036","charityBankName":"Guide Dogs for the Blnd Association of Queensland","charityBsbCode":"034014","charityAccountNumber":"129206","charityDescription":"As a leader in the provision of Guide Dogs and Mobility Services, Guide Dogs Queensland is dedicated to ensuring people with low or no\r\nvision have access to the services they need. We rely on the support of the community to help fund our vital services, including our\r\niconic Guide Dogs so that vision-impaired Queenslanders can live with independence, mobility, and freedom. The demand for our\r\nservice is continually growing as the incidence of vision impairment increases, and we deliver by assisting people of all ages with\r\nour wide range of aids. All programs and services are tailored to match the needs and lifestyles of each individual, with most training\r\ndelivered locally in the person’s home, community or work environment.","charityNotes":"","updatedAt":"2021-06-02T10:00:35.543Z","createdAt":"2019-11-05T22:36:28.894Z","isDeleted":false,"isActive":true,"isDefault":false,"registeredGift":"Yes","registeredACNC":"Yes","__v":0,"deleted":false,"statesOperatingIn":["269"],"affiliate_link":"","isStaticDonation":false,"partner_referrer_code":"guide","staticDonationAmount":0}],"Retailer_data":[{"_id":"60f8eb6fb0482c35d61dbeb3","location":{"type":"Point","coordinates":[151.2021367,-33.8651342]},"is_onboarding":true,"role":"retailer","statesOperatingIn":[],"registeredACNC":"Yes","registeredGift":"Yes","isDefault":false,"isActive":true,"newsletter_subscription":false,"date_of_birth":"2003-07-17T07:00:50.640Z","user_location_preference":[],"shopping_from":"Australia","country_id":13,"state_name_id":null,"hub_spot_id":null,"deleted":false,"is_updated_good_cause":false,"firstname":"Magento Demo","lastname":"Retailer","email":"demoretailer@mailinator.com","password":"$2a$08$V4/DHOFrFwNu0y/RlXb.dOo6mUEC//WrGtm8DBLxEydEpb2lTh14u","phoneNumber":"0412345678","shop_name":"Magento Demo Retailer","shop_desc":"Magento Demo Retailer","shop_image":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/Tags/1626925936015.jpg","industry":"Select Industry","shop_abn":"","shop_reference":"","auspost_store_id":"","shop_url":"demo_retailer","bussiness_desc":"","shop_mobile_number":"","shop_website_address":"","isLive":"1","shippingCountry":13,"shippingAddress":"300 Barangaroo Av","shippingSuburb":"Sydney","shippingState":266,"shippingPostcode":"2000","average_order_val":"Select Average Order Value?","product_sell_sku":"How many products do you sell (SKUs)","total_annual_sales":"Total Annual Sales?","point_of_sale":"","inventory_system":"","exit_inventory_system":"Do you have an existing API into your Inventory Management system?","transactionChargePercentage":10,"freeExpressShippingAbove":100,"freeStandardShippingAbove":100,"freeSendleShippingAbove":100,"orderEmailCC":"","maxReturnDays":10,"shop_bank_account_name":"","shop_bank_name":"","shop_bsb_code":"","shop_account_number":"","bt_account_manager":"","sendle_api_key":"","sendle_id":"","affiliate_link":"","shop_notes":"","createdAt":"2021-07-22T03:52:15.965Z","bannerImage":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/bannerImage/retailer/26925936097p7btdia.jpg","__v":0,"banner_position":"115.625px","freeInternationalShippingAbove":null,"freeSendleInternationalAbove":null,"policy_description":"","sendle_plan_name":"Easy","shippingAddress_1":"","updatedAt":"2021-08-24T06:06:33.106Z","shipping_options":{"international_shipping":{"timeframe":"","charge":0},"shipping_option":["Instore","Standard","ExpressDelivery"],"shipping_option_charges":"10","standard_shipping_timeframe":"3-4 days"}}],"Customer_data":[{"_id":"61248ef29b1aed428c6e6945","location":{"coordinates":[]},"is_onboarding":false,"role":"customer","statesOperatingIn":[],"registeredACNC":"Yes","registeredGift":"Yes","isDefault":false,"isActive":true,"newsletter_subscription":false,"date_of_birth":null,"user_location_preference":[],"shopping_from":"Australia","country_id":13,"state_name_id":null,"hub_spot_id":null,"deleted":false,"is_updated_good_cause":false,"firstname":"halo","lastname":"halo","email":"halo@gmail.com","password":"$2a$08$9/V.Zd5YB52k2PUIP6BQweMJTY9TVXp.A2vUh2uxfQQLrdmFo8C2e","createdAt":"2021-08-24T06:17:22.988Z","stripe_customer_id":"cus_K657qutheJwMHt","verify_token":"4w3kDoFX","__v":0,"referrer":null,"charity_id":"5dc1f96ccbbf5c085defb1bc","phoneNumber":"","updatedAt":"2021-08-24T06:17:50.208Z"}],"Shipping_data":[{"_id":"61248fb49b1aed428c6e6949","location":{"coordinates":[153.3985378,-28.0891855],"type":"Point"},"deleted":false,"is_default":true,"user_id":"61248ef29b1aed428c6e6945","save_address_as":"Home","address":"1 Abbeytree Court","Suburb":"ROBINA","state":269,"postcode":"4226","first_name":"test","phonenumber":"9929182541","country":13,"last_name":null,"createdAt":"2021-08-24T06:20:36.891Z","updatedAt":"2021-08-24T06:20:36.891Z","__v":0}],"year":"2021","month":"08","day":"24"},{"_id":"612494ef1befab31f4767aa4","is_multi":true,"shipment_response":[],"sendle_label_url":[],"order_status":"inprogress","order_generated":false,"is_cancelled":false,"is_shopify_tracking_status":false,"shopify_order_id":null,"customer_id":"61248ef29b1aed428c6e6945","total_price":28.46,"shipping_price":16.85,"payable_shipping_fee":16.85,"charity_price":0,"referrer_fee":0,"shipping_type":"","track_url":"","address_id":"61248fb49b1aed428c6e6949","sendle_remittance_amount":0,"transtation_id":"ch_3JRtLXLRWu5IWm1i0G3H5eGp","order_type":"buyit","transaction_fee":1.28,"created_by":"place_order","recipient_name":"test null","order_date":"24/08/2021","order_time":"16:42","createdAt":"2021-08-24T06:42:55.661Z","stripe_card_data":"VISA 4242","stripe_data":"{\"id\":\"ch_3JRtLXLRWu5IWm1i0G3H5eGp\",\"object\":\"charge\",\"amount\":2846,\"amount_captured\":2846,\"amount_refunded\":0,\"application\":null,\"application_fee\":null,\"application_fee_amount\":null,\"balance_transaction\":\"txn_3JRtLXLRWu5IWm1i0WK0qe8T\",\"billing_details\":{\"address\":{\"city\":null,\"country\":null,\"line1\":null,\"line2\":null,\"postal_code\":null,\"state\":null},\"email\":null,\"name\":\"test\",\"phone\":null},\"calculated_statement_descriptor\":\"Stripe\",\"captured\":true,\"created\":1629787375,\"currency\":\"aud\",\"customer\":\"cus_K657qutheJwMHt\",\"description\":\"612494ef1befab31f4767aa4\",\"destination\":null,\"dispute\":null,\"disputed\":false,\"failure_code\":null,\"failure_message\":null,\"fraud_details\":{},\"invoice\":null,\"livemode\":false,\"metadata\":{},\"on_behalf_of\":null,\"order\":null,\"outcome\":{\"network_status\":\"approved_by_network\",\"reason\":null,\"risk_level\":\"normal\",\"risk_score\":34,\"seller_message\":\"Payment complete.\",\"type\":\"authorized\"},\"paid\":true,\"payment_intent\":null,\"payment_method\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"payment_method_details\":{\"card\":{\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":null,\"cvc_check\":null},\"country\":\"US\",\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"installments\":null,\"last4\":\"4242\",\"network\":\"visa\",\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"},\"receipt_email\":null,\"receipt_number\":null,\"receipt_url\":\"https://pay.stripe.com/receipts/acct_1FcUxPLRWu5IWm1i/ch_3JRtLXLRWu5IWm1i0G3H5eGp/rcpt_K65WfyOIxIdXdKObW8OQwWaK8CgF86f\",\"refunded\":false,\"refunds\":{\"object\":\"list\",\"data\":[],\"has_more\":false,\"total_count\":0,\"url\":\"/v1/charges/ch_3JRtLXLRWu5IWm1i0G3H5eGp/refunds\"},\"review\":null,\"shipping\":null,\"source\":{\"id\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"object\":\"card\",\"address_city\":null,\"address_country\":null,\"address_line1\":null,\"address_line1_check\":null,\"address_line2\":null,\"address_state\":null,\"address_zip\":null,\"address_zip_check\":null,\"brand\":\"Visa\",\"country\":\"US\",\"customer\":\"cus_K657qutheJwMHt\",\"cvc_check\":null,\"dynamic_last4\":null,\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"last4\":\"4242\",\"metadata\":{\"futureReference\":\"true\"},\"name\":\"test\",\"tokenization_method\":null},\"source_transfer\":null,\"statement_descriptor\":null,\"statement_descriptor_suffix\":null,\"status\":\"succeeded\",\"transfer_data\":null,\"transfer_group\":null}","Order_product":[],"Product_data":[],"Charity_data":[],"Retailer_data":[],"Customer_data":[{"_id":"61248ef29b1aed428c6e6945","location":{"coordinates":[]},"is_onboarding":false,"role":"customer","statesOperatingIn":[],"registeredACNC":"Yes","registeredGift":"Yes","isDefault":false,"isActive":true,"newsletter_subscription":false,"date_of_birth":null,"user_location_preference":[],"shopping_from":"Australia","country_id":13,"state_name_id":null,"hub_spot_id":null,"deleted":false,"is_updated_good_cause":false,"firstname":"halo","lastname":"halo","email":"halo@gmail.com","password":"$2a$08$9/V.Zd5YB52k2PUIP6BQweMJTY9TVXp.A2vUh2uxfQQLrdmFo8C2e","createdAt":"2021-08-24T06:17:22.988Z","stripe_customer_id":"cus_K657qutheJwMHt","verify_token":"4w3kDoFX","__v":0,"referrer":null,"charity_id":"5dc1f96ccbbf5c085defb1bc","phoneNumber":"","updatedAt":"2021-08-24T06:17:50.208Z"}],"Shipping_data":[{"_id":"61248fb49b1aed428c6e6949","location":{"coordinates":[153.3985378,-28.0891855],"type":"Point"},"deleted":false,"is_default":true,"user_id":"61248ef29b1aed428c6e6945","save_address_as":"Home","address":"1 Abbeytree Court","Suburb":"ROBINA","state":269,"postcode":"4226","first_name":"test","phonenumber":"9929182541","country":13,"last_name":null,"createdAt":"2021-08-24T06:20:36.891Z","updatedAt":"2021-08-24T06:20:36.891Z","__v":0}],"year":"2021","month":"08","day":"24"},{"_id":"6124949b1befab31f4767a9e","is_multi":false,"shipment_response":[],"sendle_label_url":[],"order_status":"inprogress","order_generated":false,"is_cancelled":false,"is_shopify_tracking_status":false,"shopify_order_id":null,"customer_id":"61248ef29b1aed428c6e6945","total_price":24.53,"shipping_price":10,"payable_shipping_fee":10,"charity_price":0,"referrer_fee":0,"charity_id":"5dc1f96ccbbf5c085defb1bc","shipping_type":"Standard","track_url":"","transtation_id":"ch_3JRtK8LRWu5IWm1i0UwNvB6n","stripe_data":"{\"id\":\"ch_3JRtK8LRWu5IWm1i0UwNvB6n\",\"object\":\"charge\",\"amount\":2453,\"amount_captured\":2453,\"amount_refunded\":0,\"application\":null,\"application_fee\":null,\"application_fee_amount\":null,\"balance_transaction\":\"txn_3JRtK8LRWu5IWm1i00akHlS5\",\"billing_details\":{\"address\":{\"city\":null,\"country\":null,\"line1\":null,\"line2\":null,\"postal_code\":null,\"state\":null},\"email\":null,\"name\":\"test\",\"phone\":null},\"calculated_statement_descriptor\":\"Stripe\",\"captured\":true,\"created\":1629787288,\"currency\":\"aud\",\"customer\":\"cus_K657qutheJwMHt\",\"description\":\"612494971befab31f4767a9d\",\"destination\":null,\"dispute\":null,\"disputed\":false,\"failure_code\":null,\"failure_message\":null,\"fraud_details\":{},\"invoice\":null,\"livemode\":false,\"metadata\":{},\"on_behalf_of\":null,\"order\":null,\"outcome\":{\"network_status\":\"approved_by_network\",\"reason\":null,\"risk_level\":\"normal\",\"risk_score\":16,\"seller_message\":\"Payment complete.\",\"type\":\"authorized\"},\"paid\":true,\"payment_intent\":null,\"payment_method\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"payment_method_details\":{\"card\":{\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":null,\"cvc_check\":null},\"country\":\"US\",\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"installments\":null,\"last4\":\"4242\",\"network\":\"visa\",\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"},\"receipt_email\":null,\"receipt_number\":null,\"receipt_url\":\"https://pay.stripe.com/receipts/acct_1FcUxPLRWu5IWm1i/ch_3JRtK8LRWu5IWm1i0UwNvB6n/rcpt_K65VyddznSok42tpV2TQ1zJ132R8SFS\",\"refunded\":false,\"refunds\":{\"object\":\"list\",\"data\":[],\"has_more\":false,\"total_count\":0,\"url\":\"/v1/charges/ch_3JRtK8LRWu5IWm1i0UwNvB6n/refunds\"},\"review\":null,\"shipping\":null,\"source\":{\"id\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"object\":\"card\",\"address_city\":null,\"address_country\":null,\"address_line1\":null,\"address_line1_check\":null,\"address_line2\":null,\"address_state\":null,\"address_zip\":null,\"address_zip_check\":null,\"brand\":\"Visa\",\"country\":\"US\",\"customer\":\"cus_K657qutheJwMHt\",\"cvc_check\":null,\"dynamic_last4\":null,\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"last4\":\"4242\",\"metadata\":{\"futureReference\":\"true\"},\"name\":\"test\",\"tokenization_method\":null},\"source_transfer\":null,\"statement_descriptor\":null,\"statement_descriptor_suffix\":null,\"status\":\"succeeded\",\"transfer_data\":null,\"transfer_group\":null}","stripe_card_data":"VISA 4242","retailer_id":"60f8eb6fb0482c35d61dbeb3","address_id":"61248fb49b1aed428c6e6949","sendle_remittance_amount":10,"order_type":"buyit","transaction_fee":1.59,"created_by":"place_order","recipient_name":"test null","master_id":"612494971befab31f4767a9d","order_date":"24/08/2021","order_time":"16:41","createdAt":"2021-08-24T06:41:31.852Z","Order_product":[{"_id":"6124949c1befab31f4767a9f","order_id":"6124949b1befab31f4767a9e","product_id":"6120d3d9d65818cad79397d2","price":"14.53","variance_id":"6120d3d9d65818cad79397d5","quantity":1,"createdAt":"2021-08-24T06:41:32.248Z","updatedAt":"2021-08-24T06:41:32.248Z","__v":0}],"Product_data":[{"_id":"6120d3d9d65818cad79397d2","percentage_off":0,"strikethrough":14.53,"can_be_bundled":"No","product_id":["8516"],"sku_code":[],"upc_code":[],"ean_code":[],"barcode":[],"gs1_code":[],"gtin_code":[],"size_guide_id":"","Tags":[""],"deleted":false,"domain":"","status":true,"sort_order":393385093,"product_name":"Aurora - Smokey Bear - 10\" Smokey Bear","prod_details":"10 inches in size. High Quality Materials make for a soft and fluffy touch. Sweet lovable facial expression. Quality materials for a soft cuddling experience. Based on the original Smokey bear design.","dimensions":{"length":25.3,"weight_unit":"kg","_id":"61248c34c0406ca6599e33cd","dimension_value":"Extra Small Satchel -500g (275x350x10)","width":33.5,"height":4.5,"weight":1},"rrp":14.53,"image_style_rule":"contain","external_source":"magento","external_product_id":"8516","imported_product_name":"Aurora - Smokey Bear - 10\" Smokey Bear","categories":[{"sort_weigth":1000,"_id":"5d5fbe24e6802f178f97403e","path":"Kids","slug":"kids","selected":true},{"sort_weigth":1000,"_id":"603f216dd39af08f527a95c3","path":"Kids/Accessories","slug":"kids-accessories","selected":true},{"sort_weigth":1000,"_id":"5e1e841bdbf19077b5cfbb2c","path":"Kids/Baby","slug":"kids-baby","selected":false},{"sort_weigth":1000,"_id":"5f90f0ef18eb7f336d0f611d","path":"Kids/Baby/Sleeping","slug":"kids-baby-sleeping","selected":false},{"sort_weigth":1000,"_id":"603ec9f14777bb73e3adfc6f","path":"Kids/Baby/Sleeping/Baby Blankets","slug":"kids-baby-sleeping-baby-blankets","selected":true},{"sort_weigth":1000,"_id":"5e1e844fdbf19077b5cfbb2e","path":"Kids/Teens","slug":"kids-teens","selected":true}],"manufacturer":{"isActive":true,"name":"Mattel","slug":"mattel","_id":"60590c657204bd38c70665cd"},"attributes":[{"values":["6120d3d9d65818cad79397d3"],"_id":"6044736588e4572ec77fcf34"}],"images":[{"id":null,"_id":"61248f79c0406ca6599e33fa","image_order":1,"image_url":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/Products/6120d3d9d65818cad79397d2/297859777807df9i40.jpg"}],"variances":[{"image_urls":[],"is_available":true,"deleted":false,"un_product_id":[],"un_sku_code":[""],"un_gtin_code":[],"un_upc_code":[],"un_ean_code":[],"un_barcode":[],"un_gs1_code":[],"_id":"6120d3d9d65818cad79397d5","attributes":[{"_id":"6044736588e4572ec77fcf34","name":"Title","val_id":"6120d3d9d65818cad79397d3","val_name":"Aurora - Smokey Bear - 10\" Smokey Bear"}],"order":1,"is_base_variance":false,"variance_id":"8516","createdAt":"2021-08-21T10:22:17.839Z","updatedAt":"2021-08-24T06:41:35.332Z"}],"retailer_products":[{"is_active":true,"is_visible":true,"external_status":true,"shipping_option":["Instore","Standard","ExpressDelivery"],"shipping_option_charges":[0,0,10],"transaction_charge_option":false,"buy_price":14.53,"discounted_price":0,"compare_at_price":0,"is_unique":false,"policy_description_option":false,"hideFromFront":false,"_id":"6120d3d9d65818cad79397da","retailer_id":"60f8eb6fb0482c35d61dbeb3","transaction_charge_percentage":null,"standard_shipping_timeframe":"3-4 days","policy_description_val":null,"maxReturnDays":5,"international_shipping":{"timeframe":"","charge":0},"stocks":[{"old_stock":2,"discounted_price":0,"compare_at_price":0,"_id":"6120d3d9d65818cad79397d9","variance_id":"6120d3d9d65818cad79397d5","stock":1,"buy_price":14.53}]}],"product_tabs":[],"createdAt":"2021-08-21T10:22:17.840Z","updatedAt":"2021-08-24T06:41:35.332Z","slug":"aurora-smokey-bear-10-smokey-bear","__v":0}],"Charity_data":[{"_id":"5dc1f96ccbbf5c085defb1bc","firstName":"Rebecca","lastName":"McCormack","email":"fundraising@guidedogsqld.com.au","registeredCharityName":"Guide Dogs Queensland","registeredAbnName":"GUIDE DOGS FOR THE BLIND ASSOCIATION OF QUEENSLAND","charityAbn":"89009739664","charityPhoneNumber":"0735009088","charityMobileNnumber":"0735009088","charityWebsiteAddress":"https://www.guidedogsqld.com.au/","charityFacebookAddress":"https://www.facebook.com/guidedogsqld/","charityLogo":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/charityLogo/2262803551830yq2ks.jpg","bannerImage":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/bannerImage/979018275m8e8t5v8z.jpg","charityAddress":"1978 Gympie Rd","charitySuburb":"Bald Hills","charityCountry":13,"charityState":269,"charityPostcode":"4036","charityBankName":"Guide Dogs for the Blnd Association of Queensland","charityBsbCode":"034014","charityAccountNumber":"129206","charityDescription":"As a leader in the provision of Guide Dogs and Mobility Services, Guide Dogs Queensland is dedicated to ensuring people with low or no\r\nvision have access to the services they need. We rely on the support of the community to help fund our vital services, including our\r\niconic Guide Dogs so that vision-impaired Queenslanders can live with independence, mobility, and freedom. The demand for our\r\nservice is continually growing as the incidence of vision impairment increases, and we deliver by assisting people of all ages with\r\nour wide range of aids. All programs and services are tailored to match the needs and lifestyles of each individual, with most training\r\ndelivered locally in the person’s home, community or work environment.","charityNotes":"","updatedAt":"2021-06-02T10:00:35.543Z","createdAt":"2019-11-05T22:36:28.894Z","isDeleted":false,"isActive":true,"isDefault":false,"registeredGift":"Yes","registeredACNC":"Yes","__v":0,"deleted":false,"statesOperatingIn":["269"],"affiliate_link":"","isStaticDonation":false,"partner_referrer_code":"guide","staticDonationAmount":0}],"Retailer_data":[{"_id":"60f8eb6fb0482c35d61dbeb3","location":{"type":"Point","coordinates":[151.2021367,-33.8651342]},"is_onboarding":true,"role":"retailer","statesOperatingIn":[],"registeredACNC":"Yes","registeredGift":"Yes","isDefault":false,"isActive":true,"newsletter_subscription":false,"date_of_birth":"2003-07-17T07:00:50.640Z","user_location_preference":[],"shopping_from":"Australia","country_id":13,"state_name_id":null,"hub_spot_id":null,"deleted":false,"is_updated_good_cause":false,"firstname":"Magento Demo","lastname":"Retailer","email":"demoretailer@mailinator.com","password":"$2a$08$V4/DHOFrFwNu0y/RlXb.dOo6mUEC//WrGtm8DBLxEydEpb2lTh14u","phoneNumber":"0412345678","shop_name":"Magento Demo Retailer","shop_desc":"Magento Demo Retailer","shop_image":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/Tags/1626925936015.jpg","industry":"Select Industry","shop_abn":"","shop_reference":"","auspost_store_id":"","shop_url":"demo_retailer","bussiness_desc":"","shop_mobile_number":"","shop_website_address":"","isLive":"1","shippingCountry":13,"shippingAddress":"300 Barangaroo Av","shippingSuburb":"Sydney","shippingState":266,"shippingPostcode":"2000","average_order_val":"Select Average Order Value?","product_sell_sku":"How many products do you sell (SKUs)","total_annual_sales":"Total Annual Sales?","point_of_sale":"","inventory_system":"","exit_inventory_system":"Do you have an existing API into your Inventory Management system?","transactionChargePercentage":10,"freeExpressShippingAbove":100,"freeStandardShippingAbove":100,"freeSendleShippingAbove":100,"orderEmailCC":"","maxReturnDays":10,"shop_bank_account_name":"","shop_bank_name":"","shop_bsb_code":"","shop_account_number":"","bt_account_manager":"","sendle_api_key":"","sendle_id":"","affiliate_link":"","shop_notes":"","createdAt":"2021-07-22T03:52:15.965Z","bannerImage":"https://s3-ap-southeast-2.amazonaws.com/betterthat-dev/bannerImage/retailer/26925936097p7btdia.jpg","__v":0,"banner_position":"115.625px","freeInternationalShippingAbove":null,"freeSendleInternationalAbove":null,"policy_description":"","sendle_plan_name":"Easy","shippingAddress_1":"","updatedAt":"2021-08-24T06:06:33.106Z","shipping_options":{"international_shipping":{"timeframe":"","charge":0},"shipping_option":["Instore","Standard","ExpressDelivery"],"shipping_option_charges":"10","standard_shipping_timeframe":"3-4 days"}}],"Customer_data":[{"_id":"61248ef29b1aed428c6e6945","location":{"coordinates":[]},"is_onboarding":false,"role":"customer","statesOperatingIn":[],"registeredACNC":"Yes","registeredGift":"Yes","isDefault":false,"isActive":true,"newsletter_subscription":false,"date_of_birth":null,"user_location_preference":[],"shopping_from":"Australia","country_id":13,"state_name_id":null,"hub_spot_id":null,"deleted":false,"is_updated_good_cause":false,"firstname":"halo","lastname":"halo","email":"halo@gmail.com","password":"$2a$08$9/V.Zd5YB52k2PUIP6BQweMJTY9TVXp.A2vUh2uxfQQLrdmFo8C2e","createdAt":"2021-08-24T06:17:22.988Z","stripe_customer_id":"cus_K657qutheJwMHt","verify_token":"4w3kDoFX","__v":0,"referrer":null,"charity_id":"5dc1f96ccbbf5c085defb1bc","phoneNumber":"","updatedAt":"2021-08-24T06:17:50.208Z"}],"Shipping_data":[{"_id":"61248fb49b1aed428c6e6949","location":{"coordinates":[153.3985378,-28.0891855],"type":"Point"},"deleted":false,"is_default":true,"user_id":"61248ef29b1aed428c6e6945","save_address_as":"Home","address":"1 Abbeytree Court","Suburb":"ROBINA","state":269,"postcode":"4226","first_name":"test","phonenumber":"9929182541","country":13,"last_name":null,"createdAt":"2021-08-24T06:20:36.891Z","updatedAt":"2021-08-24T06:20:36.891Z","__v":0}],"year":"2021","month":"08","day":"24"},{"_id":"612494971befab31f4767a9d","is_multi":true,"shipment_response":[],"sendle_label_url":[],"order_status":"inprogress","order_generated":false,"is_cancelled":false,"is_shopify_tracking_status":false,"shopify_order_id":null,"customer_id":"61248ef29b1aed428c6e6945","total_price":24.53,"shipping_price":10,"payable_shipping_fee":10,"charity_price":0,"referrer_fee":0,"shipping_type":"","track_url":"","address_id":"61248fb49b1aed428c6e6949","sendle_remittance_amount":0,"transtation_id":"ch_3JRtK8LRWu5IWm1i0UwNvB6n","order_type":"buyit","transaction_fee":1.59,"created_by":"place_order","recipient_name":"test null","order_date":"24/08/2021","order_time":"16:41","createdAt":"2021-08-24T06:41:27.989Z","stripe_card_data":"VISA 4242","stripe_data":"{\"id\":\"ch_3JRtK8LRWu5IWm1i0UwNvB6n\",\"object\":\"charge\",\"amount\":2453,\"amount_captured\":2453,\"amount_refunded\":0,\"application\":null,\"application_fee\":null,\"application_fee_amount\":null,\"balance_transaction\":\"txn_3JRtK8LRWu5IWm1i00akHlS5\",\"billing_details\":{\"address\":{\"city\":null,\"country\":null,\"line1\":null,\"line2\":null,\"postal_code\":null,\"state\":null},\"email\":null,\"name\":\"test\",\"phone\":null},\"calculated_statement_descriptor\":\"Stripe\",\"captured\":true,\"created\":1629787288,\"currency\":\"aud\",\"customer\":\"cus_K657qutheJwMHt\",\"description\":\"612494971befab31f4767a9d\",\"destination\":null,\"dispute\":null,\"disputed\":false,\"failure_code\":null,\"failure_message\":null,\"fraud_details\":{},\"invoice\":null,\"livemode\":false,\"metadata\":{},\"on_behalf_of\":null,\"order\":null,\"outcome\":{\"network_status\":\"approved_by_network\",\"reason\":null,\"risk_level\":\"normal\",\"risk_score\":16,\"seller_message\":\"Payment complete.\",\"type\":\"authorized\"},\"paid\":true,\"payment_intent\":null,\"payment_method\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"payment_method_details\":{\"card\":{\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":null,\"cvc_check\":null},\"country\":\"US\",\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"installments\":null,\"last4\":\"4242\",\"network\":\"visa\",\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"},\"receipt_email\":null,\"receipt_number\":null,\"receipt_url\":\"https://pay.stripe.com/receipts/acct_1FcUxPLRWu5IWm1i/ch_3JRtK8LRWu5IWm1i0UwNvB6n/rcpt_K65VyddznSok42tpV2TQ1zJ132R8SFS\",\"refunded\":false,\"refunds\":{\"object\":\"list\",\"data\":[],\"has_more\":false,\"total_count\":0,\"url\":\"/v1/charges/ch_3JRtK8LRWu5IWm1i0UwNvB6n/refunds\"},\"review\":null,\"shipping\":null,\"source\":{\"id\":\"card_1JRszoLRWu5IWm1ihiP7Hr3X\",\"object\":\"card\",\"address_city\":null,\"address_country\":null,\"address_line1\":null,\"address_line1_check\":null,\"address_line2\":null,\"address_state\":null,\"address_zip\":null,\"address_zip_check\":null,\"brand\":\"Visa\",\"country\":\"US\",\"customer\":\"cus_K657qutheJwMHt\",\"cvc_check\":null,\"dynamic_last4\":null,\"exp_month\":10,\"exp_year\":2025,\"fingerprint\":\"mQddwyMtwK3o31pr\",\"funding\":\"credit\",\"last4\":\"4242\",\"metadata\":{\"futureReference\":\"true\"},\"name\":\"test\",\"tokenization_method\":null},\"source_transfer\":null,\"statement_descriptor\":null,\"statement_descriptor_suffix\":null,\"status\":\"succeeded\",\"transfer_data\":null,\"transfer_group\":null}","Order_product":[],"Product_data":[],"Charity_data":[],"Retailer_data":[],"Customer_data":[{"_id":"61248ef29b1aed428c6e6945","location":{"coordinates":[]},"is_onboarding":false,"role":"customer","statesOperatingIn":[],"registeredACNC":"Yes","registeredGift":"Yes","isDefault":false,"isActive":true,"newsletter_subscription":false,"date_of_birth":null,"user_location_preference":[],"shopping_from":"Australia","country_id":13,"state_name_id":null,"hub_spot_id":null,"deleted":false,"is_updated_good_cause":false,"firstname":"halo","lastname":"halo","email":"halo@gmail.com","password":"$2a$08$9/V.Zd5YB52k2PUIP6BQweMJTY9TVXp.A2vUh2uxfQQLrdmFo8C2e","createdAt":"2021-08-24T06:17:22.988Z","stripe_customer_id":"cus_K657qutheJwMHt","verify_token":"4w3kDoFX","__v":0,"referrer":null,"charity_id":"5dc1f96ccbbf5c085defb1bc","phoneNumber":"","updatedAt":"2021-08-24T06:17:50.208Z"}],"Shipping_data":[{"_id":"61248fb49b1aed428c6e6949","location":{"coordinates":[153.3985378,-28.0891855],"type":"Point"},"deleted":false,"is_default":true,"user_id":"61248ef29b1aed428c6e6945","save_address_as":"Home","address":"1 Abbeytree Court","Suburb":"ROBINA","state":269,"postcode":"4226","first_name":"test","phonenumber":"9929182541","country":13,"last_name":null,"createdAt":"2021-08-24T06:20:36.891Z","updatedAt":"2021-08-24T06:20:36.891Z","__v":0}],"year":"2021","month":"08","day":"24"}],"recordsTotal":0,"recordsFiltered":0}';
            $count = 0;

            if (isset($response['data']) && count($response['data']) > 0) {
                foreach ($response['data'] as $order) {
                    $BetterthatOrderId = $order['_id'];
                    $BetterthatOrder = $this->orders->create()
                        ->getCollection()
                        ->addFieldToFilter('Betterthat_order_id', $BetterthatOrderId);
                    $magentoOrderId = $BetterthatOrder->getColumnValues('increment_id');
                    if (!$this->validateString($BetterthatOrder->getData())) {
                        $customer = $this->getCustomer($order, $websiteId);
                        if ($customer !== false) {
                            $count = $this->generateQuote($store, $customer, $order, $count);
                        } else {
                            continue;
                        }
                    }else{
                        $this->webapiResponse =
                        [
                            'success' => true,
                            'message' => 'Magento order already created',
                            'orderId'=> @$magentoOrderId[0],
                            'btorderId'=>@$BetterthatOrderId
                        ];
                    }
                }
            }

            if($data)
                return $this->webapiResponse;

            if ($count > 0) {
                $this->notificationSuccess($count);
                $this->messageManager->addSuccessMessage($count. ' BT Orders successfully imported');
                return true;
            }elseif($this->failedCount > 0){
                    $this->messageManager->addComplexErrorMessage(
                        'failedOrders',
                        [
                            'url' => 'betterthat/failedorder/index/'
                        ]
                    );
                return false;
            }else{
                $this->messageManager->addErrorMessage("No New Orders found!");
            }

            return false;
        } catch (\Exception $e) {
            $this->logger->error('Import Order', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * @param $string
     * @return bool
     */
    public function validateString($string)
    {
        $stringValidation = (isset($string) && !empty($string)) ? true : false;
        return $stringValidation;
    }

    public function getEmail($order)
    {
        $customerId = $this->config->getDefaultCustomer();
        if ($customerId === false && isset($order['customer']['customer_id']) && $order['customer']['customer_id']){
            $customerCustomEmail = $order['customer']['customer_id'].'@Betterthat.com.au';
            return $customerCustomEmail;
        } else {
            return $customerId;
        }
    }

    /**
     * @param $order
     * @param $websiteId
     * @return bool|\Magento\Customer\Model\Customer
     */
    public function getCustomer($order, $websiteId)
    {
        try {
            $customer_data  = $order['Customer_data'][0];
            // Case 2.1 Get Customer if already exists.

            $customer = $this->customerFactory->create()
                ->setWebsiteId($websiteId)
                ->loadByEmail($customer_data['email']);

            if (!isset($customer) or empty($customer) or empty($customer->getData())) {
                // Case 2.1 : Create customer if does not exists.
                try {
                    $customer = $this->customerFactory->create();
                    $storeId = $this->config->getStore();
                    $store = $this->storeManager->getStore($storeId);
                    $customer->setStore($store);
                    $customer->setWebsiteId($websiteId);
                    $customer->setEmail($customer_data['email']);
                    $customer->setFirstname(
                        (isset($customer_data['firstname']) and !empty($customer_data['firstname']))
                            ? $customer_data['firstname'] : '.'
                    );
                    $customer->setLastname(
                        (isset($customer_data['lastname']) and !empty($customer_data['lastname'])) ?
                            $customer_data['lastname'] : '.'
                    );
                    $customer->setPassword($customer_data['password']);
                    $customer->save();


                } catch (\Exception $e) {
                    $reason = 'Customer create failed. Order Id: #' .
                        $order['_id'] . ' Message:' . $e->getMessage();
                    $this->rejectOrder($order, $order['Product_data'], $reason);
                    $this->logger->log(
                        'ERROR',
                        $reason
                    );
                    return false;
                }
            }

            return $customer;
        } catch (\Exception $e) {
            $this->rejectOrder($order, $order['Product_data'], $e->getMessage());
            $this->logger->error('Create Customer', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * @param string   $store
     * @param $customer
     * @param array    $order
     * @param integer  $count
     * @return mixed
     */
    public function generateQuote(
        $store,
        $customer,
        $order = null,
        $count = 0
    ) {
        $shippingcost = 0;
        $cart_id = $this->cartManagementInterface->createEmptyCart();
        $quote = $this->cartRepositoryInterface->get($cart_id);
        $quote->setStore($store);
        $quote->setCurrency();
        $quote->setCustomerNoteNotify(false);
        $customer = $this->customerRepository->getById($customer->getId());
        $quote->assignCustomer($customer);
        $itemAccepted = 0;
        $subTotal = 0;
        $rejectItemsArray = $acceptItemsArray = [];

        try {

            $reason = [];
            $qtyArray = [];
            //**grab qty
            foreach ($order['Order_product'] as $prod_data){
                $qtyArray[$prod_data['product_id']] = $prod_data['quantity'];
            }
            $priceArr =[];
            $orderSubtotal = 0;
            if (isset($order['Product_data'][0])) {
                $failedOrder = false;
                foreach ($order['Product_data'] as $item) {
                    $item['product_id'] = @$item['product_id'][0] ? $item['product_id'][0] : '';
/*                    $sku = [
                        '8517' => '30',
                        '8516' => '60'
                    ];
                    // override only for demo purpose..
                    $item['product_id'] = @$sku[$item['product_id']];*/
                    if (isset($item['product_id'])) {
                        $qty = $qtyArray[$item['_id']];
                        $product = $this->product->create()->load($item['product_id']);
                        if (isset($product) and !empty($product) and $product->getId()) {
                            $product = $this->product->create()->load($product->getEntityId());
                            if ($product->getStatus() == '1') {
                                $stockStatus = $this->checkStockQtyStatus($product, $qty);
                                if($stockStatus) {
                                    $itemAccepted++;
                                    $price = $item['rrp'];
                                    $orderSubtotal = $orderSubtotal + $price;
                                    $baseprice = $qty * $price;
                                    $priceArr[$item['product_id']] = $price;
                                    $product->setPrice($price)
                                        ->setBasePrice($baseprice)
                                        ->setSpecialPrice($baseprice)
                                        ->setOriginalCustomPrice($price)
                                        ->setRowTotal($baseprice)
                                        ->setBaseRowTotal($baseprice);
                                    $quote->addProduct($product, (int)$qty);

                                } else {
                                    $reason[] = $item['product_id'] . " Product is out of stock";
                                    $failedOrder = true;

                                }
                            } else {
                                $reason[] = $item['product_id'] . " Product id is not enabled on store";
                                $failedOrder = true;
                            }
                        } else {
                            $reason[] = $item['product_id'] . " product id does not exist on store";
                            $failedOrder = true;

                        }
                    } else {
                        $reason[] = "product id not exist in order item";
                        $failedOrder = true;

                    }
                }

                if ($failedOrder) {
                    $this->webapiResponse =
                        [
                            'success' => false,
                            'message' => @$reason[0],
                            'orderId'=> 'N/A',
                            'btorderId'=>@$order['_id']
                        ];
                    $this->rejectOrder($order, $order['Product_data'], $reason);
                } else if(!$failedOrder) {
                    $shippingData = $order['Shipping_data'];
                    $countryCode = isset($order['Country_Name']['id'])
                        ? ($order['Country_Name']['id'] == 13 && $order['Country_Name']['name'] == 'Australia' ? 'AU' : 'AU') : 'AU';
                    $stateName = @$order['State_Name']['name'] ? $order['State_Name']['name'] : @$shippingData['state'];

                    $stateModel = $this->objectManager->create('Magento\Directory\Model\RegionFactory')->create()
                        ->getCollection()->addFieldToFilter('country_id', $countryCode)->addFieldToFilter('name', ['like' => '%'.$stateName.'%'])
                            ->getFirstItem();
                    if($stateModel && $stateModel->getCode()) {
                        $stateCode = $stateModel->getCode();
                    }


                    try {
                        $shipAddress = [
                            'firstname' => @$shippingData['first_name'] ? @$shippingData['first_name'] : $order['Customer_data'][0]['firstname']  ,
                            'lastname' => @$shippingData['last_name'] ? @$shippingData['last_name'] : $order['Customer_data'][0]['lastname'],
                            'street' => @$shippingData['address'] ? $shippingData['address'] .', '. @$shippingData['Suburb']  : '',
                            'city' => @$shippingData['state'] ? $shippingData['state'] : 'N/A',
                            'country' =>  $countryCode,
                            'country_id' => $countryCode,
                            'region' => $stateCode,
                            'postcode' => $shippingData['postcode'],
                            'telephone' => @$shippingData['phonenumber'] ? @$shippingData['phonenumber'] : 'N/A',
                            'fax' => '',
                            'company' => '',
                            'save_in_address_book' => 1
                        ];


                        $this->registerShippingAmount($order['shipping_price']);
                        $this->registerShippingTaxPercentage($this->taxHelper->getShippingTaxRate($store));
                        $quote->getBillingAddress()->addData($shipAddress);
                        $shippingAddress = $quote->getShippingAddress()->addData($shipAddress);
                        $shippingMethod = 'shipbyBetterthat_shipbyBetterthat';
                        $shippingAddress->setCollectShippingRates(true)->collectShippingRates()
                            ->setShippingMethod($shippingMethod);

                        $rate = $shippingAddress->getShippingRateByCode($shippingMethod);
                        if (!$rate instanceof \Magento\Quote\Model\Quote\Address\Rate) {
                            $rate = $this->rateFactory->create();
                        }
                        $titles  = [
                            'Standard' => 'Standard Delivery - Retailer Managed',
                            'Instore' => 'Instore',
                            'International'=> 'International Delivery - Retailer Managed',
                            'InternationalDelivery' => 'International Delivery',
                            'StandardDeliverySendle' => 'Standard Delivery - Sendle',
                            'ExpressDelivery' => 'Express Delivery'
                            ];
                            $shipTitle = @$titles[$order['shipping_type']];
                            $rate->setCode($shippingMethod)
                            ->setMethod($shippingMethod)
                            ->setMethodTitle($shipTitle)
                            ->setCarrier('shipbyBetterthat')
                            ->setCarrierTitle('Betterthat Shipping')
                            ->setPrice($shippingcost)
                            ->setAddress($shippingAddress);
                        $shippingAddress->addShippingRate($rate);

                        $quote->setPaymentMethod('paybyBetterthat');
                        $quote->setInventoryProcessed(false);
                        $quote->save();
                        $quote->getPayment()->importData(
                            [
                            'method' => 'paybyBetterthat'
                            ]
                        );

                        $quote->collectTotals()->save();
                        foreach ($quote->getAllItems() as $item) {
                            $price = @$priceArr[$item->getProductId()];
                            $item->setBasePrice($price);
                            $item->setSpecialPrice($price);
                            $item->setDiscountAmount(0);
                            $item->setBaseDiscountAmount(0);
                            $item->setPrice($price);
                            $item->setRowTotal($price);
                            $item->setBaseRowTotal($price);
                            $item->setOriginalCustomPrice($price);
                            $item->setPriceIncTax($price);
                            $item->setBasePriceIncTax($price);
                            $item->setRowTotalIncTax($price);
                            $item->setBaseRowTotalIncTax($price);
                            $item->setOriginalPrice($price)->save();
                        }
                        $this->changeQuoteControl->forceIsAllowed(true);
                        $magentoOrder = $this->cartManagementInterface->submit($quote);
                        $subTotal = $order['total_price'];
                        $shippingcost = $order['shipping_price'];
                        $magentoOrder->setShippingAmount($shippingcost)
                            ->setBaseShippingAmount($shippingcost)
                            ->setShippingInclTax($shippingcost)
                            ->setBaseShippingInclTax($shippingcost)
                            ->setBaseSubTotal($orderSubtotal)
                            ->setSubTotal($orderSubtotal)
                            ->setGrandTotal($subTotal)
                            ->setIncrementId($this->config->getOrderIdPrefix() . $magentoOrder->getIncrementId())
                            ->setTotalDue(0)
                            ->save();
                        $count = isset($magentoOrder) ? $count + 1 : $count;
                        foreach ($magentoOrder->getAllItems() as $item) {
                            $price = @$priceArr[$item->getProductId()];
                            $item->setBasePrice($price);
                            $item->setSpecialPrice($price);
                            $item->setDiscountAmount(0);
                            $item->setBaseDiscountAmount(0);
                            $item->setPrice($price);
                            $item->setRowTotal($price);
                            $item->setBaseRowTotal($price);
                            $item->setOriginalCustomPrice($price);
                            $item->setPriceIncTax($price);
                            $item->setBasePriceIncTax($price);
                            $item->setRowTotalIncTax($price);
                            $item->setBaseRowTotalIncTax($price);
                            $item->setOriginalPrice($price)->save();
                        }

                        // after save order
                        $orderData = [
                            'Betterthat_order_id' => $order['_id'],
                            'order_place_date' => $order['createdAt'],
                            'magento_order_id' => $magentoOrder->getId(),
                            'increment_id' => $magentoOrder->getIncrementId(),
                            'status' => $order['order_status'],
                            'order_data' => $this->json->jsonEncode($order),
                            'order_items' => $this->json->jsonEncode($order['Product_data'])
                        ];

                        $this->orders->create()->addData($orderData)->save($this->orders);
                        $this->webapiResponse = [
                            'success' => true,
                            'message' => 'Magento order created successfully',
                            'orderId'=>@$magentoOrder->getIncrementId(),
                            'btorderId'=>@$order['_id']
                        ];
                        $this->generateInvoice($magentoOrder);

                        /*$autoAccept = $this->config->getAutoAcceptOrderSetting();
                        if($autoAccept) {
                            $this->autoOrderAccept($order['_id'], $acceptItemsArray);
                            $this->generateInvoice($magentoOrder);
                        }
                        $holdOrder = $this->config->getHoldOrderUntilShipping();
                        if($holdOrder && $magentoOrder->canHold()) {
                            $magentoOrder->hold()->save();
                        }*/
                        //$this->addTransactionToOrder($magentoOrder, $order['order_id']);
                        /*$autoCancellation = $this->config->getAutoCancelOrderSetting();
                        if($autoCancellation) {
                            $this->autoOrderAccept($order['order_id'], $rejectItemsArray);
                        }*/
                        $this->sendMail($order['_id'], $magentoOrder->getIncrementId(), $order['createdAt']);

                    } catch (\Exception $exception) {

                        $reason[] = $exception->getMessage();
                        $orderFailed = $this->orderFailed->create()->load($order['_id'], 'Betterthat_order_id');
                        $addData = [
                            'Betterthat_order_id' => @$order['_id'],
                            'status' => @$order['order_status'],
                            'reason' => $this->json->jsonEncode($reason),
                            'order_date' => @$order['createdAt'],
                            'order_data' => $this->json->jsonEncode($order),
                            'order_items' => isset($order['Product_data']) ? $this->json->jsonEncode($order['Product_data']) : '',
                        ];
                        $this->failedCount++;
                        $orderFailed->addData($addData)->save($this->orderFailed);
                        $this->logger->error('Generate Quote', ['path' => __METHOD__, 'exception' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
                    }
                }
            }

            return $count;
        } catch (\Exception $e) {
            $this->logger->error('Generate Quote', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->rejectOrder($order, [$product->getSku()], [$e->getMessage()]);
            return false;
        }
    }

    /**
     * @param array $order
     * @param array $items
     * @param array $reason
     * @return bool
     */
    public function rejectOrder(array $order, array $items = [], array $reason = [])
    {
        try {
            $orderFailed = $this->orderFailed->create()->load($order['_id'], 'Betterthat_order_id');
            $addData = [
                'Betterthat_order_id' => $order['_id'],
                'status' => $order['order_status'],
                'reason' => $this->json->jsonEncode($reason),
                'order_date' => $order['createdAt'],
                'order_data' => $this->json->jsonEncode($order),
                'order_items' => isset($order['Product_data']) ? $this->json->jsonEncode($order['Product_data']) : '',
            ];

            $orderFailed->addData($addData)->save($this->orderFailed);
            $this->failedCount++;
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Reject Order', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    public function autoOrderAccept($BetterthatOrderId, $acceptanceArray)
    {
        $acceptanceData = array(
                'order' => array(
                    '_attribute' => array(),
                    '_value' => array(
                        'order_lines' => array(
                            '_attribute' => array(),
                            '_value' => $acceptanceArray
                        )
                    )
                )
        );
        $BetterthatOrder = $this->objectManager->create(
                '\BetterthatSdk\Order',
                ['config' => $this->config->getApiConfig()]
            );
        $response = $BetterthatOrder->acceptrejectOrderLines($BetterthatOrderId, $acceptanceData);
        $this->logger->info('Auto Accept Order Acceptance Data', ['path' => __METHOD__, 'AcceptanceData' => json_encode($acceptanceData)]);
            try {
                $BetterthatOrder = $this->orders->create()
                    ->getCollection()
                    ->addFieldToFilter('Betterthat_order_id', $BetterthatOrderId)->getData();

                if (!empty($BetterthatOrder)) {
                    $id = $BetterthatOrder [0] ['id'];
                    $model = $this->orders->create()->load($id);
                    $model->setStatus('WAITING_DEBIT');
                    $model->save();
                }
            } catch (\Exception $e) {
                $this->logger->error('Auto Accept Order', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return false;
            }
        return $response;
    }
    public function getShipmentProviders()
    {
        $providers = [];
        $BetterthatOrder = $this->objectManager->create(
            '\BetterthatSdk\Order',
            ['config' => $this->config->getApiConfig()]
        );
        $BetterthatProviders = $BetterthatOrder->getShippingMethods();

        if (isset($BetterthatProviders)) {
            $providers = $BetterthatProviders;
        }
        return $providers;
    }

    public function getCancelReasons($type = 'canceled')
    {
        $reasons = [];
        $BetterthatOrder = $this->objectManager->create(
            '\BetterthatSdk\Order',
            ['config' => $this->config->getApiConfig()]
        );
        $BetterthatReasons = $BetterthatOrder->getCancelReasons();
        if (count($BetterthatReasons)) {
            $reasons = $BetterthatReasons;
        }
        return $reasons;
    }

    /**
     * @param $BetterthatOrderId
     * @param $mageOrderId
     * @param $placeDate
     * @return bool
     */
    public function sendMail($betterthatOrderId, $mageOrderId, $placeDate)
    {
        $to_email = $this->scopeConfig->getValue('betterthat_config/betterthat_order/order_notify_email');
        try {
            if ($to_email) {

                    /** @var \Magento\Framework\DataObject $data */
                    $data = $this->dataFactory->create();
                    $data->addData([
                        'to' => $to_email,
                        'marketplace_name' => 'Betterthat',
                        'po_id' => $betterthatOrderId,
                        'order_id' => $mageOrderId,
                        'order_date' => $placeDate,
                    ]);
                    /** @var \Ced\Betterthat\Model\Mail $mail */
                    $mail = $this->mailFactory->create();
                    $mail->send($data);

            }
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Send Mail', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * @param $order
     */
    public function generateInvoice($order)
    {
        try {
            $invoice = $this->objectManager->create('Magento\Sales\Model\Service\InvoiceService')
                ->prepareInvoice($order);
            $invoice->register();
            $invoice->save();
            $transactionSave = $this->objectManager->create('Magento\Framework\DB\Transaction')
                ->addObject($invoice)->addObject($invoice->getOrder());
            $transactionSave->save();
            $order->addStatusHistoryComment(__('Notified customer about invoice #%1.', $invoice->getId()))
                ->setIsCustomerNotified(false)->save();
            $order->setStatus('processing')->save();
        } catch (\Exception $e) {
            $this->logger->error('Generate Magento Invoice', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * Ship Betterthat Order
     *
     * @param  array $data
     * @return array
     */
    public function shipOrder(array $data = [])
    {
        $response = [
            'success' => false,
            'message' => [],
            'status' => ""
        ];

        try {
            /*$jsontoship = '{
                "order_id":"sdc",
                "tracking_info":{ "order_id": "ORE665465", "tracking_info":
                { "shipping_company": "ABC", "shipping_date": "23/09/2022", "tracking_number": "TRACK35356", "tracking_url": "https://abc.com/track/TRACK35356", "notes":"any" } }
            }';*/
            $arraytoship = [
                    "order_id" => $data['BetterthatOrderID'],
                    "tracking_info" => [
                        "shipping_company"=> $data['ShippingProvider'],
                        "shipping_date" => $data['OrderShipDate'],
                        "tracking_number" => $data['TrackingNumber'],
                        "tracking_url" => "",
                        "notes" => ""
                    ]
            ];

            $order = $this->objectManager
                ->create('\BetterthatSdk\Order', ['config' => $this->config->getApiConfig()]);

            $this->logger->info('Ship Order Tracking Update', ['path' => __METHOD__, 'ShipData' => var_export($data, true), 'TrackingData' => var_export($arraytoship, true), 'ShipResponseData' => var_export($response, true)]);
            if ($arraytoship) {
                $status = $order->putShipOrder($arraytoship);
                $this->logger->info('Ship Order Status Update', ['path' => __METHOD__, 'ShipData' => var_export($arraytoship, true), 'ShipResponseData' => var_export($status, true)]);
                return $response;
            } else {
                $response['message'][] = 'There is some issue while shipment';
            }
        } catch (\Exception $exception) {
            $response['message'] = $exception->getMessage();
            $this->logger->error('Ship Order', ['path' => __METHOD__, 'exception' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
        }

        return $response;
    }

    /**
     * Cancel Betterthat Order
     *
     * @param  array $data
     * @return array
     */
    public function cancelOrder(array $data = [])
    {
        $response = [
            'success' => false,
            'message' => []
        ];

        try {
            $order = $this->objectManager
                ->create('\BetterthatSdk\Order', ['config' => $this->config->getApiConfig()]);
            $magentoOrder = $this->objectManager
                ->create('\Magento\Sales\Model\Order')->load($data['order_id']);
            $cancel = [];

            if (isset($data['OrderItemIds']) and !empty($data['OrderItemIds'])) {
                foreach ($data['OrderItemIds'] as $orderItemId) {
                    // Preparing cancel qty for magento credit memo
                    if (isset($orderItemId['QuantityCancelled']) and !empty($orderItemId['QuantityCancelled'])) {
                        $cancelQty = [];
                        foreach ($magentoOrder->getAllItems() as $orderItem) {
                            if ($orderItem->getSku() == $orderItemId['SKU']) {
                                $cancelQty[$orderItem->getId()] = $orderItemId['QuantityCancelled'];
                            }
                        }
                    } else {
                        throw new \Exception('QuantityCancelled are missing.');
                    }

                    // Preparing to cancel from Betterthat
                    if (isset($orderItemId['OrderItemId']) and !empty($orderItemId['OrderItemId'])) {
                        $cancel['OrderItemId'] = $orderItemId['OrderItemId'];
                    } else {
                        throw new \Exception('OrderItemId are missing.');
                    }

                    if (isset($orderItemId['Reason']) and !empty($orderItemId['Reason'])) {
                        $cancel['Reason'] = $orderItemId['Reason'];
                    } else {
                        throw new \Exception('Reasons are missing.');
                    }

                    $status = $order->cancelOrderItem($cancel);
                    if ($status->getStatus() !== \BetterthatSdk\Api\Response::REQUEST_STATUS_FAILURE) {
                        $this->generateCreditMemo($magentoOrder, $cancelQty);
                        $response['message'][] = $orderItemId['SKU'].' Cancelled successfully. ';
                        $response['success'] = true;
                        // Saving fulfillment data.
                        $BetterthatOrder = $this->orders->create()->load($data['order_id'], 'magento_order_id');

                        $data['Status'] = $status->getStatus();
                        $data['Response'] = $response['message'];

                        $cancellations = [];
                        if (!empty($BetterthatOrder->getData('cancellations'))) {
                            $cancellations = $this->json->jsonDecode($BetterthatOrder->getData('cancellations'));
                        }
                        $cancellations[] = $data;

                        $BetterthatOrder->setData('cancellations', $this->json->jsonEncode($cancellations));
                        $BetterthatOrder->setData('status', \Ced\Betterthat\Model\Source\Order\Status::SHIPPED);
                        $BetterthatOrder->save();
                    } else {
                        $response['message'][] = $orderItemId['SKU']." ". $status->getError();
                    }
                }
            } else {
                throw new \Exception('OrderItemIds are missing.');
            }
        } catch (\Exception $exception) {
            $response['message'] = $exception->getMessage();
            $this->logger->error('Cancel Order', ['path' => __METHOD__, 'exception' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
        }
        return $response;
    }

    /**
     * @param $count
     * @throws \Exception
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function notificationSuccess($count)
    {
        $model = $this->inbox;
        $date = date("Y-m-d H:i:s");
        $model->setData('severity', 4);
        $model->setData('date_added', $date);
        $model->setData('title', "New Betterthat Orders");
        $model->setData('description', "Congratulation! You have received " . $count . " new orders form Betterthat");
        $model->setData('url', "#");
        $model->setData('is_read', 0);
        $model->setData('is_remove', 0);
        $model->getResource()->save($model);
    }

    /**
     * @param $order
     * @param $cancelleditems
     */
    public function generateShipment($order, $cancelleditems)
    {
        $shipment = $this->prepareShipment($order, $cancelleditems);
        if ($shipment) {
            $shipment->register();
            $shipment->getOrder()->setIsInProcess(true);
            try {
                $transactionSave = $this->objectManager->create('Magento\Framework\DB\Transaction')
                    ->addObject($shipment)->addObject($shipment->getOrder());
                $transactionSave->save();
                $order->setStatus('complete')->save();
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage('Error in saving shipping:' . $e->getMessage());
                $this->logger->error('Generate Magento Shipment', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
        }
    }

    /**
     * @param $order
     * @param $cancelleditems
     * @return bool
     */
    public function prepareShipment($order, $cancelleditems)
    {
        $shipment = $this->objectManager->get('Magento\Sales\Model\Order\ShipmentFactory')
            ->create($order, isset($cancelleditems) ? $cancelleditems : [], []);
        if (!$shipment->getTotalQty()) {
            return false;
        }
        return $shipment;
    }

    /**
     * @param $order
     * @param $cancelleditems
     */

    public function generateCreditMemo($order, $cancelleditems, $shippingAmount = null)
    {
        try {
            foreach ($order->getAllItems() as $orderItems) {
                $items_id = $orderItems->getId();
                $order_id = $orderItems->getOrderId();
            }
            $creditmemoLoader = $this->creditmemoLoaderFactory->create();
            $creditmemoLoader->setOrderId($order_id);
            foreach ($cancelleditems as $item_id => $cancelQty) {
                $creditmemo[$item_id] = ['qty' => $cancelQty];
            }
            $items = [
                'items' => $creditmemo,
                'do_offline' => '1',
                'comment_text' => 'Betterthat Cancelled Orders',
                'shipping_amount' => $shippingAmount,
                'adjustment_positive' => '0',
                'adjustment_negative' => '0'
            ];
            $creditmemoLoader->setCreditmemo($items);
            $creditmemo = $creditmemoLoader->load();
            $creditmemoManagement = $this->objectManager
                ->create('Magento\Sales\Api\CreditmemoManagementInterface');
            if ($creditmemo) {
                $creditmemo->setOfflineRequested(true);
                $creditmemoManagement->refund($creditmemo, true);
                return $creditmemo->getIncrementId();
            }
        } catch (\Exception $exception) {
            $this->logger->error('Generate Magento CreditMemo', ['path' => __METHOD__, 'exception' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
            return false;
        }
    }

    /**
     * @param $message
     * @throws \Exception
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function notificationFailed($message)
    {
        $date = date("Y-m-d H:i:s");
        $model = $this->inbox;
        $model->setData('severity', 1);
        $model->setData('date_added', $date);
        $model->setData('title', "Failed Betterthat Order");
        $model->setData('description', "You have one pending order." . $message);
        $model->setData('url', "#");
        $model->setData('is_read', 0);
        $model->setData('is_remove', 0);
        $model->getResource()->save($model);
    }

    public function processOrderItems($order)
    {

        $items = [];

        $BetterthatOrderItemsData = json_decode($order->getOrderItems(), true); // update

        if(isset($BetterthatOrderItemsData['order_line'])) {
                $items = $BetterthatOrderItemsData['order_line'];
        }

        return $items;
    }

    /**
     * Save Response to db
     *
     * @param  array $response
     * @return boolean
     */
    public function saveResponse($response = [])
    {
        //remove index if already set.
        $this->registry->unregister('Betterthat_product_errors');
        if (is_array($response->getBody())) {
            try {
                $this->registry->register(
                    'Betterthat_product_errors',
                    $response->getBody()
                );
                $feedModel = $this->feeds->create();
                $feedModel->addData(
                    [
                    'feed_id' => $response->getRequestId(),
                    'type' => $response->getResponseType(),
                    'feed_response' => $this->json->jsonEncode(
                        ['Body' => $response->getBody(), 'Errors' => $response->getError()]
                    ),
                    'status' => (string)$response->getStatus(),
                    'feed_file' => $response->getFeedFile(),
                    'response_file' => $response->getFeedFile(),
                    'feed_created_date' => $this->dateTime->date("Y-m-d"),
                    'feed_executed_date' => $this->dateTime->date("Y-m-d"),
                    'product_ids' => $this->json->jsonEncode($this->ids)
                    ]
                );
                $feedModel->save();
                return true;
            } catch (\Exception $e) {
                $this->logger->error('Save Response', ['path' => __METHOD__, 'exception' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
            }
        }
        return false;
    }

    public function getCountryId($iso3_code)
    {
        $country_id = substr($iso3_code, 0,2);
        $country = $this->objectManager->create('\Magento\Directory\Model\Country')->loadByCode($iso3_code);
        if($country_id = $country->getData('country_id')) {
            $country_id = $country->getData('country_id');
        }
        if (empty($country_id)) {
            $country_id = 'US';
        }
        return $country_id;
    }

    /**
     * @return bool
     */
    public function syncOrders($orderIds)
    {
        try {
            $orderIds = implode(',', $orderIds);
            $orderList = $this->Betterthat->create(
                [
                    'config' => $this->config->getApiConfig(),
                ]
            );

            $response = $orderList->getOrders();

            $response = $orderList->getOrderByIds($orderIds);
            $count = 0;

            if ($count > 0) {
                $this->notificationSuccess($count);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->error('Sync Order', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /*
    Function to generate Credit Memo
    */
    public function createCreditMemo($increment_id, $result){
        try {
            $data = array();
            $order = $this->salesOrder->loadByIncrementId($increment_id);
            if ($result['order_state'] == 'REFUSED') {
                if ($order->getData()) {
                    if ($order->canCancel()) {
                        $order->cancel()->save();
                        $order->addStatusHistoryComment(__("Order $increment_id cancel On Magento because of order is refused on Betterthat."))
                            ->setIsCustomerNotified(false)->save();
                        $this->messageManager->addSuccessMessage("Order $increment_id CANCELED Successfully.");
                        return true;
                    }
                }
            }
            if ($order->getData()) {
                if (!isset($result['order_lines']['order_line'][0])) {
                    $result['order_lines']['order_line'] = array(
                        0 => $result['order_lines']['order_line'],
                    );
                }
                $BetterthatOrderItems = isset($result['order_lines']['order_line']) ? $result['order_lines']['order_line'] : array();
                $BetterthatOfferSkus = array_column($BetterthatOrderItems, 'offer_sku');
                $orderItem = $order->getItemsCollection()->getData();
                foreach ($orderItem as $item) {
                    $totalQuantityRefunded = 0;
                    $skuIndex = array_search($item['sku'], $BetterthatOfferSkus);
                    if (!isset($BetterthatOrderItems[$skuIndex]['refunds']['refund'][0]) && isset($BetterthatOrderItems[$skuIndex]['refunds']['refund'])) {
                        $BetterthatOrderItems[$skuIndex]['refunds']['refund'] = array(
                            0 => $BetterthatOrderItems[$skuIndex]['refunds']['refund'],
                        );
                    }
                    $refundItems = isset($BetterthatOrderItems[$skuIndex]['refunds']['refund']) ? $BetterthatOrderItems[$skuIndex]['refunds']['refund'] : array();
                    foreach ($refundItems as $refundItem) {
                        $totalQuantityRefunded += $refundItem['quantity'];
                    }
                    if (isset($refundItems) && count($refundItems) > 0) {
                        if ((int)$item['qty_invoiced'] > 0 && ((int)$item['qty_refunded'] != (int)$item['qty_invoiced']) && ((int)$item['qty_refunded'] < $totalQuantityRefunded)) {
                            $qtyToRefund = $totalQuantityRefunded - (int)$item['qty_refunded'];
                            $data['qtys'][$item['item_id']] = (int)$qtyToRefund;
                            $shippingAmount = isset($data['shipping_amount']) ? $data['shipping_amount'] : 0;
                            $data['shipping_amount'] = $shippingAmount + $refundItem['shipping_amount'];
                        }
                    }
                }
            }
            if (isset($data['qtys']) && count($data['qtys'])) {
                if (!$order->canCreditmemo()) {
                    return true;
                }
                $creditmemo_id = $this->generateCreditMemo($order, $data['qtys'], $data['shipping_amount']);
                if ($creditmemo_id != "") {
                    $order->addStatusHistoryComment(__("Credit Memo " . $creditmemo_id . " is Successfully generated for Order :" . $increment_id . "."))
                        ->setIsCustomerNotified(false)->save();
                    $this->messageManager->addSuccessMessage("Credit Memo " . $creditmemo_id . " is Successfully generated for Order :" . $increment_id . ".");
                    return true;
                }
            }
            return $this;
        } catch (\Exception $e) {
            $this->logger->error('Create Credit Memo', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }


    /**
     * @return bool
     */
    public function acknowledgeOrders($orderIds)
    {
        try {
            //$orderIds = $orderCollection->getColumnValues('Betterthat_order_id');
            $orderIds = implode(',', $orderIds);
            $orderList = $this->Betterthat->create(
                [
                    'config' => $this->config->getApiConfig(),
                ]
            );

            $response = $orderList->getOrderByIds($orderIds);
            $count = 0;
            if (isset($response['body']['orders']) && count($response['body']['orders']) > 0) {
                //case: single purchase order
                if (!isset($response['body']['orders']['order'][0])) {
                    $response['body']['orders']['order'] = array(
                        0 => $response['body']['orders']['order'],
                    );
                }

                foreach ($response['body']['orders']['order'] as $order) {
                    if (!isset($order['order_lines']['order_line'][0])) {
                        $order['order_lines']['order_line'] = array(
                            0 => $order['order_lines']['order_line'],
                        );
                    }
                    $BetterthatOrderId = $order['order_id'];
                    $BetterthatOrder = $this->orders->create()
                        ->getCollection()
                        ->addFieldToFilter('Betterthat_order_id', $BetterthatOrderId)->getFirstItem();
                    if ($this->validateString($BetterthatOrder->getData())) {
                        if (isset($order['order_lines']['order_line'])) {
                            $acceptItemsArray = [];
                            foreach ($order['order_lines']['order_line'] as $item) {
                                $lineNumber = $item['order_line_id'];
                                if ($item['order_line_state'] == "WAITING_ACCEPTANCE") {
                                    $acceptItemsArray[] = [
                                        'order_line' => [
                                            '_attribute' => [],
                                            '_value' => [
                                                'accepted' => "true",
                                                'id' => $lineNumber
                                            ]
                                        ]
                                    ];
                                }
                            }
                            $ackResponse = $this->autoOrderAccept($BetterthatOrderId, $acceptItemsArray);
                            if (!$ackResponse && count($acceptItemsArray) > 0) {
                                $count++;
                            }
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->notificationSuccess($count);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            $this->logger->error('Acknowlege Order', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }



    /**
     * @return bool
     */
    public function syncOrdersStatus($orderIds)
    {
        try {
            //$orderIds = $orderCollection->getColumnValues('Betterthat_order_id');
            $orderIds = implode(',', $orderIds);
            $orderList = $this->Betterthat->create(
                [
                    'config' => $this->config->getApiConfig(),
                ]
            );

            $response = $orderList->getOrderByIds($orderIds);
            $count = 0;
            if (isset($response['body']['orders']) && count($response['body']['orders']) > 0) {
                //case: single purchase order
                if (!isset($response['body']['orders']['order'][0])) {
                    $response['body']['orders']['order'] = array(
                        0 => $response['body']['orders']['order'],
                    );
                }
                foreach ($response['body']['orders']['order'] as $order) {
                    $orderFailed = $this->orderFailed->create()->load($order['order_id'], 'Betterthat_order_id');
                    $orderFailed->setStatus($order['order_state'])->save();
                    $count++;
                }
            }

            if ($count > 0) {
                $this->notificationSuccess($count);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            $this->logger->error('Sync Order Status', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * @return bool
     */
    public function rejectOrCancelOrder($orderIds)
    {
        try {
            //$orderIds = $orderCollection->getColumnValues('Betterthat_order_id');
            $orderIds = implode(',', $orderIds);
            $orderList = $this->Betterthat->create(
                [
                    'config' => $this->config->getApiConfig(),
                ]
            );

            $response = $orderList->getOrderByIds($orderIds);
            $count = 0;
            if (isset($response['body']['orders']) && count($response['body']['orders']) > 0) {
                //case: single purchase order
                if (!isset($response['body']['orders']['order'][0])) {
                    $response['body']['orders']['order'] = array(
                        0 => $response['body']['orders']['order'],
                    );
                }

                foreach ($response['body']['orders']['order'] as $order) {
                    if (!isset($order['order_lines']['order_line'][0])) {
                        $order['order_lines']['order_line'] = array(
                            0 => $order['order_lines']['order_line'],
                        );
                    }
                    $BetterthatOrderId = $order['order_id'];
                    if (isset($order['order_lines']['order_line'])) {
                        $rejectItemsArray = [];
                        foreach ($order['order_lines']['order_line'] as $item) {
                            $lineNumber = $item['order_line_id'];
                            if ($item['order_line_state'] == "WAITING_ACCEPTANCE") {
                                $rejectItemsArray[] = [
                                    'order_line' => [
                                        '_attribute' => [],
                                        '_value' => [
                                            'accepted' => "false",
                                            'id' => $lineNumber
                                        ]
                                    ]
                                ];
                            }
                        }
                        $ackResponse = $this->autoOrderAccept($BetterthatOrderId, $rejectItemsArray);
                        if (!$ackResponse && count($rejectItemsArray) > 0) {
                            $count++;
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->notificationSuccess($count);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            $this->logger->error('Reject Or Cancel Order', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }


    public function refundOnBetterthat($orderIncrementId = NULL, $cancelOrder = array(), $creditMemoID = NULL)
    {
        try {
            $cancelOrder = array(
                'body' => array(
                    'refunds' => array(
                        '_attribute' => array(),
                        '_value' => $cancelOrder
                    )
                )
            );
            $orderList = $this->Betterthat->create(
                [
                    'config' => $this->config->getApiConfig(),
                ]
            );
            $refundRes = $orderList->refundOnBetterthat($cancelOrder);
            $orderModel = $this->orders->create()
                ->getCollection()
                ->addFieldToFilter('increment_id', $orderIncrementId)->getFirstItem();
            $refundResData = array(
                'creditMemoId' => $creditMemoID,
                'requestData' => $cancelOrder,
                'responseData' => $refundRes
            );
            $cancelData = $orderModel->getData();
            if (isset($cancelData[0]['cancellations']) && $cancelData[0]['cancellations'] != '') {
                $cancelData = $this->json->jsonDecode($cancelData[0]['cancellations']);
            } else {
                $cancelData = null;
            }
            if (!is_array($cancelData)) {
                $cancelData = array();
            }
            array_push($cancelData, $refundResData);
            $cancelData = $this->json->jsonEncode($cancelData);
            $orderModel->setData('cancellations', $cancelData)->save();
            //$this->logger->addInfo('Credit Memo By Core', array('path' => __METHOD__, 'request_with_response' => $refundResData));
            return $refundRes;
        } catch (\Exception $e) {
            $this->logger->error('Refund On Betterthat', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * Ship Betterthat Order
     *
     * @param  array $data
     * @return array
     */
    public function shipOrders($BetterthatOrders)
    {
        if (count($BetterthatOrders) == 0) {
            $this->logger->info('Ship Order', ['path' => __METHOD__, 'ShipData' => 'No Orders To Ship.']);
            return false;
        } else {
            foreach ($BetterthatOrders as $BetterthatOrder) {
                $magentoOrderId = $BetterthatOrder->getIncrementId();
                $this->order = $this->objectManager->create('\Magento\Sales\Api\Data\OrderInterface');
                $order = $this->order->loadByIncrementId($magentoOrderId);
                if ($order->getStatus() == 'complete' || $order->getStatus() == 'Complete') {
                    $return = $this->prepareShipmentData($order, $BetterthatOrder);
                    if ($return) {
                        $this->logger->info('Ship Order Successfully', ['path' => __METHOD__, 'Magento Increment ID' => $magentoOrderId, 'Response Data' => var_export($return, true)]);
                    } else {
                        $this->logger->info('Ship Order Failed', ['path' => __METHOD__, 'Magento Increment ID' => $magentoOrderId, 'Response Data' => var_export($return, true)]);
                    }
                }
            }
        }
        return true;
    }

    /**
     * Shipment
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Event\Observer
     */
    public function prepareShipmentData($order = null, $BetterthatOrder = null)
    {
        try {
            $carrier_name = $carrier_code = $tracking_number = '';
            foreach ($order->getShipmentsCollection() as $shipment) {
                $alltrackback = $shipment->getAllTracks();
                foreach ($alltrackback as $track) {
                    if ($track->getTrackNumber() != '') {
                        $tracking_number = $track->getTrackNumber();
                        $carrier_code = $track->getCarrierCode();
                        $carrier_name = $track->getTitle();
                        break;
                    }
                }
            }

            $purchaseOrderId = $BetterthatOrder->getBetterthatOrderId();
            if (empty($purchaseOrderId)) {
                $this->logger->info('Ship Order', ['path' => __METHOD__, 'ShipData' => 'Not A Betterthat Order.']);
                return false;
            }

            if ($tracking_number && $BetterthatOrder->getBetterthatOrderId()) {
                $shippingProvider = $this->getShipmentProviders();
                $providerCode = array_column($shippingProvider, 'code');
                $carrier_code = (in_array(strtoupper($carrier_code), $providerCode)) ? strtoupper($carrier_code) : '';
                $args = ['TrackingNumber' => $tracking_number, 'ShippingProvider' => strtoupper($carrier_code), 'order_id' => $BetterthatOrder->getMagentoOrderId(), 'BetterthatOrderID' => $BetterthatOrder->getBetterthatOrderId(), 'ShippingProviderName' => strtolower($carrier_name)];
                $response = $this->shipOrder($args);
                $this->logger->info('Prepare Shipment Data', ['path' => __METHOD__, 'DataToShip' => json_encode($args), 'Response Data' => json_encode($response)]);
                return $response;
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->error('Refund On Betterthat', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    public function downloadOrderDocument($BetterthatOrderId)
    {
        $order = $this->objectManager
            ->create('\BetterthatSdk\Order', ['config' => $this->config->getApiConfig()]);
        /*$response = $order->getDocumentIds($BetterthatOrderId);
        if (!isset($response['body']['order_documents']['order_document'][0]) && isset($response['body']['order_documents']['order_document'])) {
            $response['body']['order_documents']['order_document'] = array(
                0 => $response['body']['order_documents']['order_document']
            );
        }
        if(is_array($response) && isset($response['body']['order_documents']['order_document'])) {
            foreach ($response['body']['order_documents']['order_document'] as $document) {
                $response = $order->downloadDocument($document);
            }
        }*/
        $response = $order->downloadDocument($BetterthatOrderId);
        if($response) {
            return true;
        } else {
            return false;
        }
    }

    public function checkStockQtyStatus($product, $qty) {
        $stockStatus = false;
        $useMSI = $this->config->getUseMsi();
        if($useMSI) {
            $msiSourceCode = $this->config->getMsiSourceCode();
            $msiSourceDataModel = $this->objectManager->create('\Magento\InventoryCatalogAdminUi\Model\GetSourceItemsDataBySku');
            $invSourceData = $msiSourceDataModel->execute($product->getSku());
            if($invSourceData && is_array($invSourceData) && count($invSourceData) > 0) {
                $invSourceData = array_column($invSourceData, 'quantity','source_code');
                $quantity = isset($invSourceData[$msiSourceCode]) ? $invSourceData[$msiSourceCode] : 0;
                $stockStatus = ($quantity > 0) ? ($quantity >= $qty ? true : false) : false;
            }
        } else {
            $stock = $this->stockRegistry
                ->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
            if($stock->getTypeId() == "configurable" && $stock->getIsInStock() == '1'){
                return true; // temp check configurable case
            }
            $stockStatus = ($stock->getQty() > 0) ? ($stock->getIsInStock() == '1' ?
                ($stock->getQty() >= $qty ? true : false)
                : false) : false;
        }
        return $stockStatus;
    }

    /*
     * @param \Magento\Sales\Model\Order $order
     */
    public function addTransactionToOrder($order, $BetterthatOrderId)
    {
        try {
            $paymentData = [];
            $paymentData['id'] = $BetterthatOrderId;
            //get payment object from order object
            $payment = $order->getPayment();
            $payment->setLastTransId($paymentData['id']);
            $payment->setTransactionId($paymentData['id']);
            $payment->setAdditionalInformation(
                [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
            );
            $formatedPrice = $order->getBaseCurrency()->formatTxt(
                $order->getGrandTotal()
            );

            $message = __('The authorized amount is %1.', $formatedPrice);
            //get the object of builder class
            $this->_transactionBuilder = $this->objectManager->create('Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface');
            $trans = $this->_transactionBuilder;
            $transaction = $trans->setPayment($payment)
                ->setOrder($order)
                ->setTransactionId($paymentData['id'])
                ->setAdditionalInformation(
                    [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
                )
                ->setFailSafe(true)
                //build method creates the transaction and returns the object
                ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);

            $payment->addTransactionCommentsToOrder(
                $transaction,
                $message
            );
            $payment->setParentTransactionId(null);
            $payment->save();
            $order->save();
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Order Payment Transaction Error', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            //log errors here
        }
    }

    /**
     * Set the total shipping in registry
     * @param float $amount
     */
    private function registerShippingAmount($amount)
    {
        $this->registry->unregister(\Ced\Betterthat\Model\Carrier\Betterthat::REGISTRY_INDEX_SHIPPING_TOTAL);

        $this->registry->register(
            \Ced\Betterthat\Model\Carrier\Betterthat::REGISTRY_INDEX_SHIPPING_TOTAL,
            $amount
        );
    }

    /**
     * Set the total shipping in registry
     * @param float $amount
     */
    private function registerShippingTaxPercentage($amount)
    {
        $this->registry->unregister(\Ced\Betterthat\Model\Carrier\Betterthat::REGISTRY_INDEX_SHIPPING_TAX_PERCENTAGE);

        $this->registry->register(
            \Ced\Betterthat\Model\Carrier\Betterthat::REGISTRY_INDEX_SHIPPING_TAX_PERCENTAGE,
            $amount
        );
    }
}
