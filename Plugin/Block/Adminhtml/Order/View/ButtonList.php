<?php
namespace Ced\Betterthat\Plugin\Block\Adminhtml\Order\View;

class ButtonList
{

    /**
     * Object Manger
     *
     * @var \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public $objectManager;

    public $request;

    public $orderRepository;

    public $btorder;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Ced\Betterthat\Model\OrdersFactory $btorder
    ) {
        $this->objectManager = $objectManager;
        $this->request = $request;
        $this->orderRepository = $orderRepository;
        $this->btorder = $btorder;
    }

    public function afterGetButtonList(
        \Magento\Backend\Block\Widget\Context $subject,
        $buttonList
    ) {
        if ($this->request->getFullActionName() == 'sales_order_view') {
            $order = $this->orderRepository->get($this->request->getParam('order_id'));
            if ($order) {
                $btorder = $this->btorder->create()->load($order->getIncrementId(), 'increment_id');
                if ($btorder) {
                    $shipDesc = $order->getShippingDescription();
                    if (str_contains($shipDesc, 'Express')
                        || str_contains($shipDesc, 'Instore')
                        || str_contains($shipDesc, 'BetterThat')) {
                        $buttonList->remove('order_ship');
                    }
                }
            }
        }

        return $buttonList;
    }
}
