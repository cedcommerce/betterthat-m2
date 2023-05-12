<?php

namespace Betterthat\Betterthat\Observer;

class InventoryChangeObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var api
     */
    protected $api;
    /**
     * @var \Betterthat\Betterthat\Helper\Logger
     */
    protected $logger;
    /**
     * @var \Betterthat\Betterthat\Helper\Config
     */
    public $config;
    /**
     * @var mixed
     */
    public $_productRepository;
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    public $objectManager;
    /**
     * @var \Betterthat\Betterthat\Helper\Product
     */
    public $product;

    /**
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     * @param \Betterthat\Betterthat\Helper\Product $product
     * @param \Betterthat\Betterthat\Helper\Config $config
     */
    public function __construct(
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Betterthat\Betterthat\Helper\Product $product,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->product = $product;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Event\Observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            if ($this->config->isValid() == "0"
                || $this->config->isValid() == null
            ) {
                return $observer;
            }
            $event = $observer->getEvent();
            if ($event->hasData('item')
                && $this->config->isValid() == 1
            ) {
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
                //debug inside update inv
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
