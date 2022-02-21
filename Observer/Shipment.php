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
 * @category    Ced
 * @package     Ced_Betterthat
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Betterthat\Observer;

class Shipment implements \Magento\Framework\Event\ObserverInterface
{
	protected $objectManager;
	protected $api;
	protected $logger;

	public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Betterthat\Helper\Logger $logger,
        \Ced\Betterthat\Helper\Order $api
    ) {
        $this->objectManager = $objectManager;
        $this->api = $api;
        $this->logger = $logger;
    }
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $this->logger->info('Shipment Observer', ['path' => __METHOD__, 'ShipData' => 'Shipment Observer Working']);
		if($observer->getEvent()->getTrack()) {
			$order_id = $observer->getEvent()->getTrack()->getOrderId();

			if($order_id) {
                try{
					$bo_order = $this->objectManager->get('Ced\Betterthat\Model\Orders')->load($order_id,'magento_order_id');
					if($bo_order && $bo_order->getData('Betterthat_order_id')) {
						$tracking_number = $observer->getEvent()->getTrack()->getTrackNumber();
						$carrier_code = $observer->getEvent()->getTrack()->getCarrierCode();
                        $carrier_name = $observer->getEvent()->getTrack()->getTitle();
						$args = ['OrderShipDate'=>date('d-m-y'), 'TrackingNumber' => $tracking_number,'ShippingProvider' => strtoupper($carrier_code), 'BetterthatOrderID' => $bo_order->getData('Betterthat_order_id'), 'ShippingProviderName' => strtolower($carrier_name) ];
						$response = $this->api->shipOrder($args);
						if(@$response['status'] == 'OK'){
                            $bo_order->setData('status','Shipped');
                            $bo_order->save($this->orderModel);
                        }
                        $this->logger->info('Shipment Data In Observer', ['path' => __METHOD__, 'DataToShip' => json_encode($args), 'Response Data' => json_encode($response)]);
					}
				} catch (\Exception $e){
				    die($e->getMessage());
                    $this->logger->error('Shipment Observer', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
				}
			}
		}
		return $this;
	}
}
