<?php

namespace Ced\Betterthat\Observer;

class Save implements \Magento\Framework\Event\ObserverInterface
{
	protected $objectManager;
	protected $productHelper;
	protected $logger;

	public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Betterthat\Helper\Logger $logger,
        \Ced\Betterthat\Helper\Product $productHelper,
        \Ced\Betterthat\Model\ResourceModel\Profile $profileResource,
        \Ced\Betterthat\Model\ProfileFactory $profileFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->objectManager = $objectManager;
        $this->productHelper = $productHelper;
        $this->logger = $logger;
        $this->profileResource = $profileResource;
        $this->profileModel = $profileFactory;
        $this->messageManager = $messageManager;
    }
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		if($product = $observer->getEvent()->getProduct()) {
			try{
                if ($product->dataHasChangedFor('betterthat_profile_id')) {
                    if($product->getbetterthat_profile_id()){
                        $profileModel = $this->profileModel->create();
                        $this->profileResource->load($profileModel,$product->getbetterthat_profile_id(),'id',);
                        if($profileModel->getId() == null)
                        {
                            $message = __('Betterthat profile id is invalid, please fill correct id and previous id has been reset.');
                            $this->messageManager->addWarningMessage($message);
                            $product->setbetterthat_profile_id(@$product->getOrigData('betterthat_profile_id'));
                        }
                    }
                }

                if($product->dataHasChangedFor('betterthat_visibility')){
                    $response = $this->productHelper->_sendBetterthatVisibility(["product_id"=>$product->getId(),"visible_status"=>$product->getBetterthatVisibility() ? "true" : "false"]);
                    if(@$response['status'] && $response['status']){
                        $this->messageManager->addSuccessMessage($response["message"]);
                    }
                }


			} catch (\Exception $e){

			}
		}
	}
}
