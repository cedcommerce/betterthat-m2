<?php
namespace Ced\Betterthat\Plugin\Block\Adminhtml\Order\View;

class ButtonList
{

    public function afterGetButtonList(
        \Magento\Backend\Block\Widget\Context $subject,
        $buttonList
    )
    {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $this->objectManager->get('Magento\Framework\App\Action\Context')->getRequest();
        if($request->getFullActionName() == 'sales_order_view'){
            $order = $this->objectManager->get('\Magento\Sales\Api\OrderRepositoryInterface')->get($request->getParam('order_id'));
            if($order){
                $btorder = $this->objectManager->get('\Ced\Betterthat\Model\OrdersFactory')->create()->load($order->getIncrementId(),'increment_id');
                if($btorder){
                    $shipDesc = $order->getShippingDescription();
                    if (str_contains($shipDesc,'Express' ) || str_contains($shipDesc,'Instore') || str_contains($shipDesc,'BetterThat') ){
                        $buttonList->remove('order_ship');
                    }
                }
            }
        }

        return $buttonList;
    }
}
