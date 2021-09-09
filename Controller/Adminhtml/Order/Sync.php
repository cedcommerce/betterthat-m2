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

namespace Ced\Betterthat\Controller\Adminhtml\Order;

class Sync extends \Magento\Backend\App\Action
{
    const CHUNK_SIZE = 1;
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ced_Betterthat::Betterthat_orders';
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    public $resultRedirectFactory;
    /**
     * @var \Ced\Betterthat\Helper\Order
     */
    public $orderHelper;

    /**
     * @var \Ced\Betterthat\Helper\Product
     */
    public $Betterthat;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;

    public $config;

    public $ordersdk;
    public $shipmentFactory;

    /**
     * Sync constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Ced\Betterthat\Helper\Order $orderHelper
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Ced\Betterthat\Model\ResourceModel\Orders $collection
     * @param \Ced\Betterthat\Helper\Product $product
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Ced\Betterthat\Helper\Order $orderHelper,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Ced\Betterthat\Model\OrdersFactory $collection,
        \Ced\Betterthat\Model\ResourceModel\Orders $resourceCollection,
        \BetterthatSdk\OrderFactory $ordersdk,
        \Ced\Betterthat\Helper\Config $config,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->orderHelper = $orderHelper;
        $this->filter = $filter;
        $this->orders = $resourceCollection;
        $this->orderModel = $collection->create();
        $this->Betterthat = $ordersdk;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session =  $context->getSession();
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->config = $config;
        $this->shipmentFactory = $shipmentFactory;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if(!$id){
            $collection = $this->filter->getCollection($this->orderModel->getCollection());
            $ids = $collection->getAllIds();
            $id = @$ids[0];
        }


        $this->orders->load($this->orderModel, $id, 'id');
        $betterthatOrderId = $this->orderModel->getData('Betterthat_order_id');
        $orderList = $this->Betterthat->create(
            [
                'config' => $this->config->getApiConfig(),
            ]
        );

        $order = $this->_objectManager->create('Magento\Sales\Model\Order')->load($this->orderModel->getData('increment_id'),'increment_id');
        $response = $orderList->getOrders('orders-list', $betterthatOrderId);
        $trackingNumber = @$response['data'][0]['shipment_response'][0]['items'][0]['tracking_details']['article_id'];
        if ($trackingNumber) {
            $titles = [
                'Instore' => 'Instore',
                'InternationalDelivery' => 'International Delivery',
                'StandardDeliverySendle' => 'Standard Delivery - Sendle',
                'ExpressDelivery' => 'Australia Post'
            ];
            $title = $titles[@$response['data'][0]['shipping_type']];
            $tracking = [[
                'carrier_code' => 'ups',
                'title' => 'United Parcel Service',//$title,
                'number' => $trackingNumber,
            ]];
        }else{
            $orderStatus = @$response['data'][0]['order_status'];
            if($orderStatus){
                $this->orderModel->setData('status',$orderStatus);
                $this->orders->save($this->orderModel);
            }
            $this->messageManager->addSuccessMessage("Ordered status & shipment synced successfully !!");
            return $this->_redirect('betterthat/order/index');
        }
        $shipment = $this->createShipment($order,$trackingNumber,$title);

        if ($shipment) {
            $orderStatus = @$response['data'][0]['order_status'];
            if($orderStatus){
                $this->orderModel->setData('status',$orderStatus);
                $this->orders->save($this->orderModel);
            }
            $this->messageManager->addSuccessMessage("Order Id : ".$order->getIncrementId()." Synced Successfully. Shipment generated!!");
            return $this->_redirect('betterthat/order/index');
        }else{
            $this->messageManager->addErrorMessage("Order Id : ".$order->getIncrementId()." Shipment Already generated!!");
            return $this->_redirect('betterthat/order/index');
        }

        // case 3.1 normal uploading if current ids are less than chunk size.
        /* if (count($orderIds) <= self::CHUNK_SIZE) {
             die('ff');
             $response = $this->orderHelper->syncOrders($orderIds);
             if ($response) {
                 $this->messageManager->addSuccessMessage(count($orderIds) . ' Order(s) Synced Successfully');
             } else {
                 $message = 'Order(s) Syncing Failed.';
                 $errors = $this->registry->registry('Betterthat_order_errors');
                 if (isset($errors)) {
                     $message = "Order(s) Syncing Failed. \nErrors: " . (string)json_encode($errors);
                 }
                 $this->messageManager->addError($message);
             }

             $resultRedirect = $this->resultFactory->create('redirect');
             $resultRedirect->setUrl($this->_redirect->getRefererUrl());
             return $resultRedirect;
         }*/
        // case 3.2 normal uploading if current ids are more than chunk size.
        $orderIds = array_chunk($orderIds, self::CHUNK_SIZE);
        $this->registry->register('orderids', count($orderIds));
        $this->session->setBetterthatOrders($orderIds);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ced_Betterthat::Betterthat');
        $resultPage->getConfig()->getTitle()->prepend(__('Sync Orders'));
        return $resultPage;

    }
    /**
     * @param int $orderId
     * @param string $trackingNumber
     * @return \Magento\Sales\Model\Shipment $shipment
     */
    protected function createShipment($order, $trackingNumber,$title)
    {
        try {
            if ($order){
                $data = array(array(
                    'carrier_code' => $order->getShippingMethod(),
                    'title' => $title,
                    'number' => $trackingNumber,
                ));
                $shipment = $this->prepareShipment($order, $data);
                if ($shipment) {
                    $order->setIsInProcess(true);
                    $order->addStatusHistoryComment('Automatically SHIPPED', false);
                    $transactionSave =  $this->_objectManager->create('Magento\Framework\DB\TransactionFactory')->create()->addObject($shipment)->addObject($shipment->getOrder());
                    $transactionSave->save();
                    return true;
                }
                return false;

            }
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
        }
    }

    /**
     * @param $order \Magento\Sales\Model\Order
     * @param $track array
     * @return $this
     */
    protected function prepareShipment($order, $track)
    {
        $shipment = $this->shipmentFactory->create(
            $order,
            $this->prepareShipmentItems($order),
            $track
        );
        return $shipment->getTotalQty() ? $shipment->register() : false;
    }

    /**
     * @param $order \Magento\Sales\Model\Order
     * @return array
     */
    protected function prepareShipmentItems($order)
    {
        $items = [];

        foreach($order->getAllItems() as $item) {
            $items[$item->getItemId()] = $item->getQtyOrdered();
        }
        return $items;
    }

}
