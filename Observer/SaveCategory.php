<?php

namespace Betterthat\Betterthat\Observer;

use function GuzzleHttp\json_decode;

class SaveCategory implements \Magento\Framework\Event\ObserverInterface
{
    protected $objectManager;
    protected $api;
    protected $logger;
    public $config;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collectionFactory,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        $this->objectManager = $objectManager;
        $this->logger = $logger;
        $this->productAction = $productAction;
        $this->storeManager = $storeManager;
        $this->profileCollection = $collectionFactory;
        $this->config = $config;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->isValid() == "0" || $this->config->isValid() == null) {
            return $observer;
        }
        $this->logger->info('category_save', ['path' => __METHOD__, 'category_data' => 'Category Observer Working']);
        $productIds = $observer->getEvent()->getProductIds();
        $categoryId = $observer->getEvent()->getCategory()->getId();
        $profileId = '';
        if ($productIds) {
            $storeIds = array_keys($this->storeManager->getStores());
            $data = $this->profileCollection
                ->create()
                ->addFieldToFilter("magento_category", ["like" => "%" . $categoryId . "%"]);
            foreach ($data as $item) {
                $magento_cat = json_decode($item->getMagentoCategory());
                if (in_array($categoryId, $magento_cat)) {
                    $profileId = $item->getId();
                }
            }

            if ($profileId) {
                foreach ($storeIds as $storeId) {
                    $this->productAction
                        ->updateAttributes($productIds, ['betterthat_profile_id' => $profileId], $storeId);
                }
            }
        }
        return $this;
    }
}
