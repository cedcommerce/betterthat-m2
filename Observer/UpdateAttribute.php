<?php

namespace Ced\Betterthat\Observer;

class UpdateAttribute implements \Magento\Framework\Event\ObserverInterface
{
    protected $api;
    protected $logger;

    public function __construct(
        \Ced\Betterthat\Helper\Logger $logger,
        \Ced\Betterthat\Helper\Product $product
    ) {
        die('ssss');
        $this->logger = $logger;
        $logger = new \Zend\Log\Logger();
        $testLog = new \Zend\Log\Writer\Stream(BP.'/var/log/attr_observer.log');
        $logger->addWriter($testLog);
        $logger->info("Mass action event is called");
        //$this->product = $product;
        //$this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$this->_productRepository = $this->objectManager->get("\Magento\Catalog\Api\ProductRepositoryInterface");
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->log('INFO','UpdateAttribute Observer Working');
        try {
            $logger = new \Zend\Log\Logger();
            $testLog = new \Zend\Log\Writer\Stream(BP.'/var/log/attr_observer.log');
            $logger->addWriter($testLog);
            $logger->info("Mass action event is called");
            /*$attributeData = $observer->getEvent()->getAttributesData();
            $productIds = $observer->getEvent()->getProductIds();*/
        } catch (\Exception $e) {
            $this->logger->error('UpdateAttribute Observer', ['path' => __METHOD__, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $observer;
        }
        return $observer;
    }
}
