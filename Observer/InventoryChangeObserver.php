<?php

namespace Ced\Betterthat\Observer;

class InventoryChangeObserver implements \Magento\Framework\Event\ObserverInterface
{
	protected $api;
	protected $logger;

	public function __construct(
        \Ced\Betterthat\Helper\Logger $logger,
        \Ced\Betterthat\Helper\Product $product
    ) {
        $this->logger = $logger;
        $this->product = $product;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_productRepository = $this->objectManager->get("\Magento\Catalog\Api\ProductRepositoryInterface");

    }
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $this->logger->log('INFO','InventorySaveAfter Observer Working');
        try {
            $event = $observer->getEvent();
            if ($event->hasData('item')) {
                $item = $event->getData('item');
                $productId = $item->getData('product_id');
                $product = $this->_productRepository->getById($productId);
                if($product->getVisibility() == 1){
                    $product = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($productId);
                    if(isset($product[0])){
                        //this is parent product id..
                        $this->logger->log('INFO','inv test Observer Working');
                        $productId = $product[0];
                    }
                }

                    $response = $this->product->updatePriceInventory([$productId]);
                    $this->logger->log('INFO','inv test Observer Working');
                    $this->logger->log('INFO',$productId);
                    $this->logger->log('INFO',$item->getData('qty'));
                    $this->logger->log('INFO',$item->getOrigData('qty'));


                    $this->logger->log('INFO',json_encode($response));

            }
        } catch (\Exception $e) {

            $this->logger->error('InventorySaveAfter Observer', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $observer;
        }
        return $observer;
	}
}
