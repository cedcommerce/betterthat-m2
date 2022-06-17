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
        $this->_productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $event = $observer->getEvent();
            if ($event->hasData('item')) {
                $item = $event->getData('item');
                $productId = $item->getData('product_id');
                $product = $this->_productRepository->getById($productId);
                if ($product->getVisibility() == 1) {
                    $product = $this->objectManager
                        ->create(\Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable::class)
                        ->getParentIdsByChild($productId);
                    if (isset($product[0])) {
                        //this is parent product id.
                        $productId = $product[0];
                    }
                }
                $this->product->updatePriceInventory([$productId]);
            }
        } catch (\Exception $e) {
            $this->logger->error(
                'InventorySaveAfter Observer',
                ['path' => __METHOD__,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return $observer;
        }
        return $observer;
    }
}
