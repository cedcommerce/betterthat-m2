<?php
/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Controller\Adminhtml\Order;

class Sync extends \Magento\Backend\App\Action
{
    public const CHUNK_SIZE = 1;
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Betterthat_Betterthat::Betterthat_orders';
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    public $resultRedirectFactory;
    /**
     * @var \Betterthat\Betterthat\Helper\Order
     */
    public $orderHelper;

    /**
     * @var \Betterthat\Betterthat\Helper\Product
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
    /**
     * @var \Betterthat\Betterthat\Helper\Config
     */
    public $config;
    /**
     * @var ordersdk
     */
    public $ordersdk;
    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    public $shipmentFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Betterthat\Betterthat\Helper\Order $orderHelper
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Betterthat\Betterthat\Model\OrdersFactory $collection
     * @param \Betterthat\Betterthat\Model\ResourceModel\Orders $resourceCollection
     * @param \BetterthatSdk\OrderFactory $ordersdk
     * @param \Betterthat\Betterthat\Helper\Config $config
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Betterthat\Betterthat\Helper\Order $orderHelper,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Betterthat\Betterthat\Model\OrdersFactory $collection,
        \Betterthat\Betterthat\Model\ResourceModel\Orders $resourceCollection,
        \BetterthatSdk\OrderFactory $ordersdk,
        \Betterthat\Betterthat\Helper\Config $config,
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
     * Execute
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if (!$id) {
            $collection = $this->filter->getCollection($this->orderModel->getCollection());
            $ids = $collection->getAllIds();
            $id = isset($ids[0]) ? $ids[0] : null;
        }
        $this->orders->load($this->orderModel, $id, 'id');
        $betterthatOrderId = $this->orderModel->getData('Betterthat_order_id');
        $orderList = $this->Betterthat->create(
            [
                'config' => $this->config->getApiConfig(),
            ]
        );
        $order = $this->_objectManager
            ->create(\Magento\Sales\Model\Order::class)
            ->load($this->orderModel->getData('increment_id'), 'increment_id');
        $response = $orderList->getOrders('orders-list', $betterthatOrderId);
        $trackingNumber =
            isset($response['data'][0]['shipment_response'][0]['items'][0]['tracking_details']['article_id'])
            ? $response['data'][0]['shipment_response'][0]['items'][0]['tracking_details']['article_id'] : null;
        if ($trackingNumber) {
            $titles = [
                'Instore' => 'Instore',
                'InternationalDelivery' => 'International Delivery',
                'StandardDeliverySendle' => 'Standard Delivery - Sendle',
                'ExpressDelivery' => 'Australia Post'
            ];
            $title = isset($titles[$response['data'][0]['shipping_type']])
                ? $titles[$response['data'][0]['shipping_type']] : '';
            $tracking = [[
                'carrier_code' => 'ups',
                'title' => 'United Parcel Service',//$title,
                'number' => $trackingNumber,
            ]];

            $orderStatus = isset($response['data'][0]['order_status'])
                ? $response['data'][0]['order_status'] : '';
            if ($orderStatus) {
                $this->orderModel->setData('status', $orderStatus);
                $this->orders->save($this->orderModel);
            }
        } else {
            $orderStatus = isset($response['data'][0]['order_status'])
                ? $response['data'][0]['order_status'] : '';
            if ($orderStatus) {
                $this->orderModel->setData('status', $orderStatus);
                $this->orders->save($this->orderModel);
            }
            $this->messageManager
                ->addSuccessMessage("Ordered status & shipment synced successfully !!");
            $resultRedirect = $this->resultFactory->create('redirect');
            return $resultRedirect->setPath('betterthat/order/index');
        }
        $shipment = $this->createShipment($order, $trackingNumber, $title);
        if ($shipment) {
            $orderStatus = isset($response['data'][0]['order_status'])
                ? $response['data'][0]['order_status'] : null;
            if ($orderStatus) {
                $this->orderModel->setData('status', $orderStatus);
                $this->orders->save($this->orderModel);
            }
            $this->messageManager->addSuccessMessage(
                "Order Id : "
                .$order->getIncrementId()
                . " Synced Successfully. Shipment generated!!"
            );
            $resultRedirect = $this->resultFactory->create('redirect');
            return $resultRedirect->setPath('betterthat/order/index');
        } else {
            $this->messageManager
                ->addErrorMessage(
                    "Order Id : "
                    . $order->getIncrementId()
                    . " Shipment Already generated!!"
                );
            $resultRedirect = $this->resultFactory->create('redirect');
            return $resultRedirect->setPath('betterthat/order/index');
        }
        // case 3.2 normal uploading if current ids are more than chunk size.
        $orderIds = array_chunk($orderIds, self::CHUNK_SIZE);
        $this->registry->register('orderids', count($orderIds));
        $this->session->setBetterthatOrders($orderIds);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Betterthat_Betterthat::Betterthat');
        $resultPage->getConfig()->getTitle()->prepend(__('Sync Orders'));
        return $resultPage;
    }

    /**
     * CreateShipment
     *
     * @param object $order
     * @param string $trackingNumber
     * @param string $title
     * @return bool|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function createShipment($order, $trackingNumber, $title)
    {
        try {
            if ($order) {
                $data = [[
                    'carrier_code' => $order->getShippingMethod(),
                    'title' => $title,
                    'number' => $trackingNumber,
                ]];
                $shipment = $this->prepareShipment($order, $data);
                if ($shipment) {
                    $order->setIsInProcess(true);
                    $order->addStatusHistoryComment('Automatically SHIPPED', false);
                    $transactionSave =  $this->_objectManager
                        ->create(\Magento\Framework\DB\TransactionFactory::class)
                        ->create()
                        ->addObject($shipment)->addObject($shipment->getOrder());
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
     * PrepareShipment
     *
     * @param  \Magento\Sales\Model\Order $order
     * @param  array $track
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
     * PrepareShipmentItems
     *
     * @param  \Magento\Sales\Model\Order $order
     * @return array
     */
    protected function prepareShipmentItems($order)
    {
        $items = [];
        foreach ($order->getAllItems() as $item) {
            $items[$item->getItemId()] = $item->getQtyOrdered();
        }
        return $items;
    }
}
