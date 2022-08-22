<?php
/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Controller\Adminhtml\Order;

use Magento\Framework\Data\Argument\Interpreter\Constant;

class MassShip extends \Magento\Backend\App\Action
{
    /**
     * ResultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * Authorization level of a basic admin session
     *
     * @var Constant
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Betterthat_Betterthat::Betterthat_orders';

    public $filter;

    public $orderManagement;

    public $order;

    public $BetterthatOrders;

    public $orderHelper;

    /**
     * MassCancel constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter    $filter
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement,
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Betterthat\Betterthat\Model\Orders $collection,
        \Betterthat\Betterthat\Helper\Order $orderHelper,
        \Betterthat\Betterthat\Helper\Logger $logger
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->filter = $filter;
        $this->orderManagement = $orderManagement;
        $this->order = $order;
        $this->BetterthatOrders = $collection;
        $this->orderHelper = $orderHelper;
        $this->logger = $logger;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $collection = $this->filter
            ->getCollection($this->BetterthatOrders->getCollection());
        $BetterthatOrders = $collection;

        if (count($BetterthatOrders) == 0) {
            $this->messageManager->addErrorMessage('No Orders To Ship.');
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setPath('betterthat/order/index');
            return;
        } else {
            $counter = 0;
            foreach ($BetterthatOrders as $BetterthatOrder) {
                $magentoOrderId = $BetterthatOrder->getIncrementId();
                $this->order = $this->_objectManager
                    ->create(\Magento\Sales\Api\Data\OrderInterface::class);
                $order = $this->order->loadByIncrementId($magentoOrderId);
                if ($order->getStatus() == 'complete' || $order->getStatus() == 'Complete') {
                    $return = $this->shipment($order, $BetterthatOrder);
                    if ($return) {
                        $counter++;
                    }
                }
            }
            if ($counter) {
                $this->messageManager
                    ->addSuccessMessage($counter . ' Orders Shipment Successfull to Betterthat.com');
            } else {
                $this->messageManager
                    ->addErrorMessage('Orders Shipment Unsuccessfull.');
            }
            $resultRedirect = $this->resultFactory->create('redirect');
            return $resultRedirect->setPath('betterthat/order/index');
        }
    }

    /**
     * Shipment
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Event\Observer
     */
    public function shipment($order = null, $BetterthatOrder = null)
    {
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
        try {
            $purchaseOrderId = $BetterthatOrder->getBetterthatOrderId();
            if (empty($purchaseOrderId)) {
                return false;
            }
            if ($tracking_number && $BetterthatOrder->getBetterthatOrderId()) {
                $shippingProvider = $this->orderHelper->getShipmentProviders();
                $providerCode = array_column($shippingProvider, 'code');
                $carrier_code = (in_array(strtoupper($carrier_code), $providerCode))
                    ? strtoupper($carrier_code) : '';
                $args = [
                    'TrackingNumber' => $tracking_number,
                    'ShippingProvider' => strtoupper($carrier_code),
                    'order_id' => $BetterthatOrder->getMagentoOrderId(),
                    'BetterthatOrderID' => $BetterthatOrder->getBetterthatOrderId(),
                    'ShippingProviderName' => strtolower($carrier_name)
                ];
                $response = $this->orderHelper->shipOrder($args);
                $this->logger->log('ERROR', json_encode($response));
                return $response;
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->log('ERROR', json_encode($e->getMessage()));
            return false;
        }
    }
}
