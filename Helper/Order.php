<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
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
        \Ced\Betterthat\Helper\Tax $taxHelper
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
    }

    /**
     * @return bool
     */
    public function importOrders()
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

            $response = $orderList->getOrders();
            $count = 0;
            if (isset($response['body']['orders']) && count($response['body']['orders']) > 0) {
                //case: single purchase order
                if (!isset($response['body']['orders']['order'][0])) {
                    $response['body']['orders']['order'] = array(
                        0 => $response['body']['orders']['order'],
                    );
                }

                foreach ($response['body']['orders']['order'] as $order) {


                    //case: single order line
                    if (!isset($order['order_lines']['order_line'][0])) {
                        $order['order_lines']['order_line'] = array(
                            0 => $order['order_lines']['order_line'],
                        );
                    }

                    $BetterthatOrderId = $order['order_id'];
                    $BetterthatOrder = $this->orders->create()
                        ->getCollection()
                        ->addFieldToFilter('Betterthat_order_id', $BetterthatOrderId);
                    if (!$this->validateString($BetterthatOrder->getData())) {
                        $customer = $this->getCustomer($order, $websiteId);
                        if ($customer !== false) {
                            $count = $this->generateQuote($store, $customer, $order, $count);
                        } else {
                            continue;
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
            /*$customerId = $this->config->getDefaultCustomer();
            if ($customerId !== false) {
                // Case 1: Use default customer.
                $customer = $this->customerFactory->create()
                    ->setWebsiteId($websiteId)
                    ->load($customerId);
                if (!isset($customer) or empty($customer)) {
                    $this->logger->log(
                        'ERROR',
                        "Default Customer does not exists. Customer Id: #{$customerId}."
                    );
                    return false;
                }
            } else {*/
            // Case 2: Use Customer from Order.
            $email = $this->getEmail($order);
            // Case 2.1 Get Customer if already exists.
            $customer = $this->customerFactory->create()
                ->setWebsiteId($websiteId)
                ->loadByEmail($email);

            if (!isset($customer) or empty($customer) or empty($customer->getData())) {
                // Case 2.1 : Create customer if does not exists.
                try {
                    $customer = $this->customerFactory->create();
                    $storeId = $this->config->getStore();
                    $store = $this->storeManager->getStore($storeId);
                    $customer->setStore($store);
                    $customer->setWebsiteId($websiteId);
                    $customer->setEmail($this->getEmail($order));
                    $customer->setFirstname(
                        (isset($order['customer']['firstname']) and !empty($order['customer']['firstname']))
                            ? $order['customer']['firstname'] : '.'
                    );
                    $customer->setLastname(
                        (isset($order['customer']['lastname']) and !empty($order['customer']['lastname'])) ?
                            $order['customer']['lastname'] : '.'
                    );
                    $customer->setPassword("Betterthatpassword");
                    $customer->save();
                } catch (\Exception $e) {
                    $this->logger->log(
                        'ERROR',
                        'Customer create failed. Order Id: #' .
                        $order['order_id'] . ' Message:' . $e->getMessage()
                    );
                    return false;
                }
            }
            //}

            return $customer;
        } catch (\Exception $e) {
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

            if (isset($order['order_lines']['order_line'])) {
                $failedOrder = false;
                foreach ($order['order_lines']['order_line'] as $item) {
                    if (isset($item['offer_sku'])) {
                        $lineNumber = $item['order_line_id'];
                        $qty = $item['quantity'];
                        $product = $this->product->create()->loadByAttribute('sku', $item['offer_sku']);
                        if (isset($product) and !empty($product)) {
                            $product = $this->product->create()->load($product->getEntityId());
                            if ($product->getStatus() == '1') {
                                /* Get stock item */
                                $stock = $this->stockRegistry
                                    ->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
                                $stockStatus = ($stock->getQty() > 0) ? ($stock->getIsInStock() == '1' ?
                                    ($stock->getQty() >= $qty ? true : false)
                                    : false) : false;
                                $stockStatus = $this->checkStockQtyStatus($product, $qty);
                                if($stockStatus) {
                                    $itemAccepted++;
                                    $price = $item['price_unit'];
                                    $baseprice = $qty * $price;
                                    $shippingcost += isset($item ['shipping_price']) ?
                                        $item ['shipping_price'] : 0;
                                    $rowTotal = $price * $qty;
                                    $subTotal += $rowTotal;
                                    $product->setPrice($price)
                                        ->setBasePrice($baseprice)
                                        ->setSpecialPrice($baseprice)
                                        ->setOriginalCustomPrice($price)
                                        ->setRowTotal($rowTotal)
                                        ->setBaseRowTotal($rowTotal);
                                    $quote->addProduct($product, (int)$qty);
                                    if($item['order_line_state'] == "WAITING_ACCEPTANCE") {
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
                                } else {
                                    $reason[] = $item['offer_sku'] . "SKU out of stock";
                                    $failedOrder = true;
                                    /*if($item['order_line_state'] == "WAITING_ACCEPTANCE") {
                                        $rejectItemsArray[] = [
                                            'order_line' => [
                                                '_attribute' => [],
                                                '_value' => [
                                                    'accepted' => "false",
                                                    'id' => $lineNumber
                                                ]
                                            ]
                                        ];
                                    }*/
                                }
                            } else {
                                $reason[] = $item['offer_sku'] . " SKU not enabled on store";
                                $failedOrder = true;
                                /*if($item['order_line_state'] == "WAITING_ACCEPTANCE") {
                                    $rejectItemsArray[] = [
                                        'order_line' => [
                                            '_attribute' => [],
                                            '_value' => [
                                                'accepted' => "false",
                                                'id' => $lineNumber
                                            ]
                                        ]
                                    ];
                                }*/
                            }
                        } else {
                            $reason[] = $item['offer_sku'] . " not exist on store";
                            $failedOrder = true;
                            /*if($item['order_line_state'] == "WAITING_ACCEPTANCE") {
                                $rejectItemsArray[] = [
                                    'order_line' => [
                                        '_attribute' => [],
                                        '_value' => [
                                            'accepted' => "false",
                                            'id' => $lineNumber
                                        ]
                                    ]
                                ];
                            }*/
                        }
                    } else {
                        $reason[] = "SKU not exist in order item";
                        $failedOrder = true;
                        /*if($item['order_line_state'] == "WAITING_ACCEPTANCE") {
                            $rejectItemsArray[] = [
                                'order_line' => [
                                    '_attribute' => [],
                                    '_value' => [
                                        'accepted' => "false",
                                        'id' => $lineNumber
                                    ]
                                ]
                            ];
                        }*/
                    }
                }

                if ($failedOrder) {
                    $this->rejectOrder($order, $order['order_lines']['order_line'], $reason);
                } else if(!$failedOrder) {
                    $countryCode = isset($order['customer']['shipping_address']['country_iso_code']) ? $order['customer']['shipping_address']['country_iso_code'] : 'AU';
                    $stateCode = 'N/A';
                    $stateModel = $this->objectManager->create('Magento\Directory\Model\RegionFactory')->create()
                        ->getCollection()->addFieldToFilter('country_id', $countryCode)->getFirstItem();
                    if($stateModel && $stateModel->getCode()) {
                        $stateCode = $stateModel->getCode();
                    }
                    try {
                        $shipping_address_street_2 = '';
                        if(isset($order['customer']['shipping_address']['street_2']) && !is_array($order['customer']['shipping_address']['street_2']))
                            $shipping_address_street_2 = $order['customer']['shipping_address']['street_2'];

                        $billing_address_street_2 = '';
                        if(isset($order['customer']['billing_address']['street_2']) && !is_array($order['customer']['billing_address']['street_2']))
                            $billing_address_street_2 = $order['customer']['billing_address']['street_2'];

                        $shipping_address_street_1 = '';
                        if(isset($order['customer']['shipping_address']['street_2']) && !is_array($order['customer']['shipping_address']['street_1']))
                            $shipping_address_street_1 = $order['customer']['shipping_address']['street_1'];

                        $billing_address_street_1 = '';
                        if(isset($order['customer']['billing_address']['street_2']) && !is_array($order['customer']['billing_address']['street_1']))
                            $billing_address_street_1 = $order['customer']['billing_address']['street_1'];

                        $shipping_address_company = '';
                        if(isset($order['customer']['shipping_address']['company']) && !is_array($order['customer']['shipping_address']['company']))
                            $shipping_address_company = $order['customer']['shipping_address']['company'];

                        $billing_address_company = '';
                        if(isset($order['customer']['billing_address']['company']) && !is_array($order['customer']['billing_address']['company']))
                            $billing_address_company = $order['customer']['billing_address']['company'];

                        $shipAddress = [
                            'firstname' => (isset($order['customer']['shipping_address']['firstname']) and
                                !empty($order['customer']['shipping_address']['firstname'])) ?
                                ($order['customer']['shipping_address']['firstname']) : $order['customer']['firstname'],
                            'lastname' => (isset($order['customer']['shipping_address']['lastname']) and
                                !empty($order['customer']['shipping_address']['lastname'])) ?
                                $order['customer']['shipping_address']['lastname'] : $order['customer']['lastname'],
                            'street' => ($shipping_address_street_1) ? $shipping_address_street_1 . " " . $shipping_address_street_2 : 'N/A',
                            'city' => isset($order['customer']['shipping_address']['city']) ? $order['customer']['shipping_address']['city']: 'N/A' ,
                            'country' =>  isset($order['customer']['shipping_address']['country']) ? $order['customer']['shipping_address']['country'] : 'N/A',
                            'country_id' => isset($order['customer']['shipping_address']['country_iso_code']) ? $this->getCountryId($order['customer']['shipping_address']['country_iso_code']) : 'AU',
                            'region' => isset($order['customer']['shipping_address']['state'])?$order['customer']['shipping_address']['state'] : $stateCode,
                            'postcode' => isset($order['customer']['shipping_address']['zip_code'])?$order['customer']['shipping_address']['zip_code'] : 'N/A',
                            'telephone' => isset($order['customer']['shipping_address']['phone']) &&
                                !empty($order['customer']['shipping_address']['phone']) ?  $order['customer']['shipping_address']['phone'] :
                                    '+1123456789',
                            'fax' => '',
                            'company' => $shipping_address_company,
                            'save_in_address_book' => 1
                        ];

                        $billAddress = [
                             'firstname' => (isset($order['customer']['billing_address']['firstname']) &&
                                !empty($order['customer']['billing_address']['firstname'])) ?
                                ($order['customer']['billing_address']['firstname']) : $order['customer']['firstname'],
                            'lastname' => (isset($order['customer']['billing_address']['lastname']) &&
                                !empty($order['customer']['billing_address']['lastname'])) ?
                                $order['customer']['billing_address']['lastname'] : $order['customer']['lastname'],
                            'street' => ( !empty($billing_address_street_1) ) ? $billing_address_street_1 . " " . $billing_address_street_2 : 'N/A'
                                ,
                            'city' => isset($order['customer']['billing_address']['city'])?$order['customer']['billing_address']['city'] : 'N/A' ,
                            'country' =>  isset($order['customer']['billing_address']['country'])?$order['customer']['billing_address']['country'] : 'N/A' ,
                            'country_id' =>  isset($order['customer']['billing_address']['country_iso_code']) ? $this->getCountryId($order['customer']['billing_address']['country_iso_code']):'AU',
                            'region' => isset($order['customer']['billing_address']['state']) ?$order['customer']['billing_address']['state']: $stateCode,
                            'postcode' => isset($order['customer']['billing_address']['zip_code'])? $order['customer']['billing_address']['zip_code']:'N/A',
                            'telephone' => isset($order['customer']['billing_address']['phone']) &&
                                !empty($order['customer']['billing_address']['phone']) ?  $order['customer']['billing_address']['phone'] :
                                    '+1123456789',
                            'fax' => '',
                            'company' => $billing_address_company,
                            'save_in_address_book' => 1
                        ];
                        $this->registerShippingAmount($shippingcost);
                        $this->registerShippingTaxPercentage($this->taxHelper->getShippingTaxRate($store));
                        $quote->getBillingAddress()->addData($billAddress);
                        $shippingAddress = $quote->getShippingAddress()->addData($shipAddress);
                        $shippingAddress->setCollectShippingRates(true)->collectShippingRates()
                            ->setShippingMethod('shipbyBetterthat_shipbyBetterthat');
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
                            $item->setDiscountAmount(0);
                            $item->setBaseDiscountAmount(0);
                            $item->setOriginalCustomPrice($item->getPrice())
                                ->setOriginalPrice($item->getPrice())->save();
                        }
                        $magentoOrder = $this->cartManagementInterface->submit($quote);
                        $magentoOrder->setShippingAmount($shippingcost)
                            ->setBaseShippingAmount($shippingcost)
                            ->setShippingInclTax($shippingcost)
                            ->setBaseShippingInclTax($shippingcost)
                            ->setGrandTotal($subTotal + $shippingcost)
                            ->setIncrementId($this->config->getOrderIdPrefix() . $magentoOrder->getIncrementId())
                            ->save();
                        $count = isset($magentoOrder) ? $count + 1 : $count;
                        foreach ($magentoOrder->getAllItems() as $item) {
                            $item->setOriginalPrice($item->getPrice())
                                ->setBaseOriginalPrice($item->getPrice())
                                ->save();
                        }

                        // after save order
                        $orderPlace = substr($order['created_date'], 0, 10);
                        $orderData = [
                            'Betterthat_order_id' => $order['order_id'],
                            'order_place_date' => $orderPlace,
                            'magento_order_id' => $magentoOrder->getId(),
                            'increment_id' => $magentoOrder->getIncrementId(),
                            'status' => $order['order_state'],
                            'order_data' => $this->json->jsonEncode($order),
                            'order_items' => $this->json->jsonEncode($order['order_lines'])
                        ];
                        $this->orders->create()->addData($orderData)->save($this->orders);
                        $autoAccept = $this->config->getAutoAcceptOrderSetting();
                        if($autoAccept) {
                            $this->autoOrderAccept($order['order_id'], $acceptItemsArray);
                            $this->generateInvoice($magentoOrder);
                        }
                        $holdOrder = $this->config->getHoldOrderUntilShipping();
                        if($holdOrder && $magentoOrder->canHold()) {
                            $magentoOrder->hold()->save();
                        }
                        //$this->addTransactionToOrder($magentoOrder, $order['order_id']);
                        /*$autoCancellation = $this->config->getAutoCancelOrderSetting();
                        if($autoCancellation) {
                            $this->autoOrderAccept($order['order_id'], $rejectItemsArray);
                        }*/
                        $this->sendMail($order['order_id'], $magentoOrder->getIncrementId(), $orderPlace);

                    } catch (\Exception $exception) {
                        $reason[] = $exception->getMessage();
                        $orderFailed = $this->orderFailed->create()->load($order['order_id'], 'Betterthat_order_id');
                        $addData = [
                            'Betterthat_order_id' => $order['order_id'],
                            'status' => $order['order_state'],
                            'reason' => $this->json->jsonEncode($reason),
                            'order_date' => substr($order['created_date'], 0, 10),
                            'order_data' => $this->json->jsonEncode($order),
                            'order_items' => isset($order['order_lines']) ? $this->json->jsonEncode($order['order_lines']) : '',
                        ];

                        $orderFailed->addData($addData)->save($this->orderFailed);
                        $this->logger->error('Generate Quote', ['path' => __METHOD__, 'exception' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
                    }
                }
            }

            return $count;
        } catch (\Exception $e) {
            $this->logger->error('Generate Quote', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->rejectOrder($order, [], [$e->getMessage()]);
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
            $orderFailed = $this->orderFailed->create()->load($order['order_id'], 'Betterthat_order_id');
            $addData = [
                'Betterthat_order_id' => $order['order_id'],
                'status' => $order['order_state'],
                'reason' => $this->json->jsonEncode($reason),
                'order_date' => substr($order['created_date'], 0, 10),
                'order_data' => $this->json->jsonEncode($order),
                'order_items' => isset($order['order_lines']) ? $this->json->jsonEncode($order['order_lines']) : '',
            ];

            $orderFailed->addData($addData)->save($this->orderFailed);
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
    public function sendMail($BetterthatOrderId, $mageOrderId, $placeDate)
    {
        try {
            $body = '<table cellpadding="0" cellspacing="0" border="0">
            <tr> <td> <table cellpadding="0" cellspacing="0" border="0">
                <tr> <td class="email-heading">
                    <h1>You have a new order from Betterthat.</h1>
                    <p> Please review your admin panel."</p>
                </td> </tr>
            </table> </td> </tr>
            <tr>
                <td>
                    <h4>Merchant Order Id' . $BetterthatOrderId . '</h4>
                </td>
                <td>
                    <h4>Magneto Order Id' . $mageOrderId . '</h4>
                </td>
                <td>
                    <h4>Order Place Date' . $placeDate . '</h4>
                </td>
            </tr>
        </table>';
            $to_email = $this->scopeConfig->getValue('Betterthat_config/Betterthat_order/order_notify_email');
            $subject = 'Imp: New Betterthat Order Imported';
            $senderEmail = 'Betterthatadmin@cedcommerce.com';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: ' . $senderEmail . '' . "\r\n";
            mail($to_email, $subject, $body, $headers);
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
            'message' => []
        ];

        try {
            $order = $this->objectManager
                ->create('\BetterthatSdk\Order', ['config' => $this->config->getApiConfig()]);
            $magentoOrder = $this->objectManager
                ->create('\Magento\Sales\Model\Order')->load($data['order_id']);
            $packed = [];
            $shipQty = [];
            foreach ($magentoOrder->getAllItems() as $orderItem) {
                $shipQty[$orderItem->getId()] = $orderItem->getQtyInvoiced();
            }
            $packed['carrier_code'] = isset($data['ShippingProvider']) ? $data['ShippingProvider'] : '';
            $packed['carrier_name'] = isset($data['ShippingProviderName']) ? $data['ShippingProviderName'] : '';
            $packed['tracking_number'] = isset($data['TrackingNumber']) ? $data['TrackingNumber'] : '';
            /*if (isset($data['ShippingProvider']) and !empty($data['ShippingProvider'])) {
                $packed['carrier_code'] = $data['ShippingProvider'];
            } else {
                throw new \Exception('ShippingProvider are missing.');
            }

            if (isset($data['TrackingNumber']) and !empty($data['TrackingNumber'])) {
                $packed['tracking_number'] = $data['TrackingNumber'];
            } else {
                throw new \Exception('TrackingNumber are missing.');
            }*/

            $status = $order->updateTrackingInfo($data['BetterthatOrderID'], $packed);
            $this->logger->info('Ship Order Tracking Update', ['path' => __METHOD__, 'ShipData' => var_export($data, true), 'TrackingData' => var_export($packed, true), 'ShipResponseData' => var_export($status, true)]);
            if (!$status) {
                $status = $order->putShipOrder($data['BetterthatOrderID']);
                $this->logger->info('Ship Order Status Update', ['path' => __METHOD__, 'ShipData' => var_export($data, true), 'ShipResponseData' => var_export($status, true)]);
                if (!$status) {
                    $this->generateShipment($magentoOrder, $shipQty);
                    $response['message'][] = 'Shipped successfully. ';
                    $response['success'] = true;
                    // Saving fulfillment data.
                    $BetterthatOrder = $this->orders->create()->load($data['order_id'], 'magento_order_id');

                    $data['OrderId'] = $data['BetterthatOrderID'];
                    $data['Status'] = \Ced\Betterthat\Model\Source\Order\Status::SHIPPED;
                    $data['Response'] = $response['message'];
                    $shipments = [];
                    if (!empty($BetterthatOrder->getData('shipments'))) {
                        $shipments = $this->json->jsonDecode($BetterthatOrder->getData('shipments'));
                    }
                    $shipments[] = $data;

                    $BetterthatOrder->setData('shipments', $this->json->jsonEncode($shipments));
                    $BetterthatOrder->setData('status', \Ced\Betterthat\Model\Source\Order\Status::SHIPPED);
                    $BetterthatOrder->save();
                } else {
                    $response['message'][] = $status;
                }
            } else {
                $response['message'][] = $status;
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
            //$orderIds = $orderCollection->getColumnValues('Betterthat_order_id');
            $orderIds = implode(',', $orderIds);
            $storeId = $this->config->getStore();
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
            $store = $this->storeManager->getStore($storeId);

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
                /*$BetterthatOrderId = isset($response['body']['orders']['order']) ? array_column($response['body']['orders']['order'], 'order_id') : array();
                $BetterthatOrder = $this->orders->create()
                    ->getCollection()
                    ->addFieldToFilter('Betterthat_order_id', $BetterthatOrderId);*/

                foreach ($response['body']['orders']['order'] as $order) {
                    $BetterthatOrderId = $order['order_id'];
                    $BetterthatOrder = $this->orders->create()
                        ->getCollection()
                        ->addFieldToFilter('Betterthat_order_id', $BetterthatOrderId)->getFirstItem();
                    $magentoOrder = $this->salesOrder->loadByIncrementId($BetterthatOrder->getIncrementId());
                    if ($this->validateString($BetterthatOrder->getData())) {
                        $shipping_address_street_2 = '';
                        if (isset($order['customer']['shipping_address']['street_2']) && !is_array($order['customer']['shipping_address']['street_2']))
                            $shipping_address_street_2 = $order['customer']['shipping_address']['street_2'];

                        $billing_address_street_2 = '';
                        if (isset($order['customer']['billing_address']['street_2']) && !is_array($order['customer']['billing_address']['street_2']))
                            $billing_address_street_2 = $order['customer']['billing_address']['street_2'];

                        $shipping_address_street_1 = '';
                        if (isset($order['customer']['shipping_address']['street_1']) && !is_array($order['customer']['shipping_address']['street_1']))
                            $shipping_address_street_1 = $order['customer']['shipping_address']['street_1'];

                        $billing_address_street_1 = '';
                        if (isset($order['customer']['billing_address']['street_1']) && !is_array($order['customer']['billing_address']['street_1']))
                            $billing_address_street_1 = $order['customer']['billing_address']['street_1'];

                        $shipping_address_company = '';
                        if (isset($order['customer']['shipping_address']['company']) && !is_array($order['customer']['shipping_address']['company']))
                            $shipping_address_company = $order['customer']['shipping_address']['company'];

                        $billing_address_company = '';
                        if (isset($order['customer']['billing_address']['company']) && !is_array($order['customer']['billing_address']['company']))
                            $billing_address_company = $order['customer']['billing_address']['company'];

                        $shipAddress = $this->repositoryAddress->get($magentoOrder->getShippingAddress()->getId());
                        if ($shipAddress->getId()) {
                            $shipAddress->setFirstname((isset($order['customer']['shipping_address']['firstname']) and
                                !empty($order['customer']['shipping_address']['firstname'])) ?
                                ($order['customer']['shipping_address']['firstname']) : $order['customer']['firstname'])
                                ->setLastname((isset($order['customer']['shipping_address']['lastname']) &&
                                    !empty($order['customer']['shipping_address']['lastname'])) ?
                                    $order['customer']['shipping_address']['lastname'] : $order['customer']['lastname'])
                                ->setStreet((!empty($shipping_address_street_1)) ? $shipping_address_street_1 . " " . $shipping_address_street_2 : 'N/A')
                                ->setCity(isset($order['customer']['shipping_address']['city']) ? $order['customer']['shipping_address']['city'] : 'N/A')
                                ->setCountry(isset($order['customer']['shipping_address']['city']) ? $order['customer']['billing_address']['city'] : 'N/A')
                                ->setCountryId(isset($order['customer']['shipping_address']['country_iso_code']) ? $this->getCountryId($order['customer']['billing_address']['country_iso_code']) : 'AU')
                                ->setRegion(isset($order['customer']['shipping_address']['state']) ? $order['customer']['shipping_address']['state'] : 'N/A')
                                ->setPostcode(isset($order['customer']['shipping_address']['zip_code']) ? $order['customer']['shipping_address']['zip_code'] : 'N/A')
                                ->setTelephone(isset($order['customer']['shipping_address']['phone']) &&
                                !empty($order['customer']['shipping_address']['phone']) ? $order['customer']['shipping_address']['phone'] :
                                    '+00000000000')
                                ->setCompany($shipping_address_company);
                            $this->repositoryAddress->save($shipAddress);
                        }
                        $billAddress = $this->repositoryAddress->get($magentoOrder->getBillingAddress()->getId());
                        if ($billAddress->getId()) {
                            $billAddress->setFirstname((isset($order['customer']['billing_address']['firstname']) and
                                !empty($order['customer']['billing_address']['firstname'])) ?
                                ($order['customer']['billing_address']['firstname']) : $order['customer']['firstname'])
                                ->setLastname((isset($order['customer']['billing_address']['lastname']) &&
                                    !empty($order['customer']['billing_address']['lastname'])) ?
                                    $order['customer']['billing_address']['lastname'] : $order['customer']['lastname'])
                                ->setStreet((!empty($billing_address_street_1)) ? $billing_address_street_1 . " " . $billing_address_street_2 : 'N/A')
                                ->setCity(isset($order['customer']['billing_address']['city']) ? $order['customer']['billing_address']['city'] : 'N/A')
                                ->setCountry(isset($order['customer']['billing_address']['city']) ? $order['customer']['billing_address']['city'] : 'N/A')
                                ->setCountryId(isset($order['customer']['billing_address']['country_iso_code']) ? $this->getCountryId($order['customer']['billing_address']['country_iso_code']) : 'AU')
                                ->setRegion(isset($order['customer']['billing_address']['state']) ? $order['customer']['billing_address']['state'] : 'N/A')
                                ->setPostcode(isset($order['customer']['billing_address']['zip_code']) ? $order['customer']['billing_address']['zip_code'] : 'N/A')
                                ->setTelephone(isset($order['customer']['billing_address']['phone']) &&
                                !empty($order['customer']['billing_address']['phone']) ? $order['customer']['billing_address']['phone'] :
                                    '+1123456789')
                                ->setCompany($billing_address_company);
                            $this->repositoryAddress->save($billAddress);
                        }
                        $BetterthatOrder->setStatus($order['order_state'])->save();
                        $count++;
                        if($order['order_state'] == 'SHIPPING' && $magentoOrder->canUnHold()) {
                            $magentoOrder->unhold()->save();
                        }
                        /*if( $order['order_state'] == 'CLOSED' || $order['order_state'] == 'CANCELED' || $order['order_state'] == 'REFUSED' || $order['order_state'] == 'SHIPPING'){
                            $cancelOrderOnMagento = $this->config->getCreditMemoOnMagento();
                            if($cancelOrderOnMagento == '1') {
                                $increment_id= $magentoOrder->getIncrementId();
                                $this->createCreditMemo($increment_id, $order);
                            }
                        }*/
                    }
                }
            }

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
