<?php

namespace Ced\Betterthat\Observer;

class Refund implements \Magento\Framework\Event\ObserverInterface
{
	protected $objectManager;
	protected $api;
	protected $logger;

	public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Betterthat\Helper\Logger $logger,
        \Ced\Betterthat\Helper\Order $api,
        \Ced\Betterthat\Model\OrdersFactory $orders,
        \Ced\Betterthat\Helper\Config $config,
        \Magento\Framework\Json\Helper\Data $json,
        \Magento\Framework\Message\ManagerInterface $manager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->objectManager = $objectManager;
        $this->api = $api;
        $this->logger = $logger;
        $this->orders = $orders;
        $this->config = $config;
        $this->json = $json;
        $this->messageManager = $manager;
        $this->_request = $request;
    }
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $this->logger->log('INFO','Refund Observer Working');
        $refundOnBetterthat = $this->config->getRefundOnBetterthat();
        $refundSkus = [];
        try {
            if ($refundOnBetterthat == "1") {
                $postData = $this->_request->getParams();
                if(isset($postData['order_id'])) {
                    $reason = (isset($postData['reason']) && $postData['reason'] != NULL) ? $postData['reason'] : $this->config->getRefundReason();
                    $creditMemo = $observer->getEvent()->getCreditmemo();
                    $creditMemoId = $creditMemo->getIncrementId();
                    $order = $creditMemo->getOrder();
                    $orderIncrementId = $order->getIncrementId();
                    $Betterthatorder = $this->orders->create()->getCollection()->addFieldToFilter('increment_id', $orderIncrementId)->getFirstItem()->getData();
                    if (count($Betterthatorder) <= 0) {
                        return $observer;
                    }
                    if (!$reason) {
                        $this->messageManager->addErrorMessage('Betterthat Refund Reason is not selected.');
                        return $observer;
                    }
                    $item = array();
                    $cancelOrder = array(
                        'refund' => array(
                            '_attribute' => array(),
                            '_value' => array()
                        )
                    );
                    $Betterthatorder_data = $this->json->jsonDecode($Betterthatorder['order_data']);
                    $Betterthatorder_data = $Betterthatorder_data['order_lines']['order_line'];
                    $order_line_ids = array_column($Betterthatorder_data, 'offer_sku');
                    foreach ($creditMemo->getAllItems() as $orderItems) {
                        $skuFound = array_search($orderItems->getSku(), $order_line_ids);
                        if ($skuFound !== FALSE) {
                            $refundSkus[] = $orderItems->getSku();
                            $item['amount'] = (string)$orderItems->getRowTotal();
                            $item['order_line_id'] = (string)$Betterthatorder_data[$skuFound]['order_line_id'];
                            $item['quantity'] = (string)$orderItems->getQty();
                            $item['reason_code'] = (string)$reason;
                            $item['shipping_amount'] = (string)((float)$Betterthatorder_data[$skuFound]['shipping_price'] / (float)$orderItems->getQty());
                        }
                        array_push($cancelOrder['refund']['_value'], $item);
                    }
                    $response = $this->api->refundOnBetterthat($orderIncrementId, $cancelOrder, /*$creditMemoId*/
                        $order->getId());

                    $this->logger->info('Refund Observer Data', ['path' => __METHOD__, 'DataToRefund' => json_encode($cancelOrder), 'Response Data' => json_encode($response)]);

                    if (isset($response['body']['refunds'])) {
                        $refundSkus = implode(', ', $refundSkus);
                        $order->addStatusHistoryComment(__("Order Items ( $refundSkus ) Refunded with $reason reason On Betterthat."))
                            ->setIsCustomerNotified(false)->save();
                        $this->logger->info('Refund Success', ['path' => __METHOD__, 'RefundSkus' => $refundSkus, 'Reason' => $reason, 'Increment Id' => $orderIncrementId]);
                        $this->messageManager->addSuccessMessage('Refund Successfully Generated on Betterthat');
                    } else {
                        $this->logger->info('Refund Fail', ['path' => __METHOD__, 'DataToRefund' => json_encode($cancelOrder), 'Response Data' => json_encode($response)]);
                        $this->messageManager->addErrorMessage('Error Generating Refund on Betterthat. Please process from merchant panel.');
                    }
                }
                return $observer;
            }
        } catch (\Exception $e) {
            $this->logger->error('Refund Observer', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $observer;
        }
        return $observer;
	}
}
