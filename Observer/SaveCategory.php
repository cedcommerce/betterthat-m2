<?php

namespace Ced\Betterthat\Observer;

class SaveCategory implements \Magento\Framework\Event\ObserverInterface
{
    protected $objectManager;
    protected $api;
    protected $logger;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Betterthat\Helper\Logger $logger,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Ced\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collectionFactory
    ) {
        $this->objectManager = $objectManager;
        $this->logger = $logger;
        $this->productAction = $productAction;
        $this->storeManager = $storeManager;
        $this->profileCollection = $collectionFactory;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->info('category_save', ['path' => __METHOD__, 'category_data' => 'Category Observer Working']);
        $productIds = $observer->getEvent()->getProductIds();
        $categoryId = $observer->getEvent()->getCategory()->getId();
        if($productIds) {
            $storeIds = array_keys($this->storeManager->getStores());
            $data = $this->profileCollection->create()->addFieldToFilter("magento_category",["like"=>"%".$categoryId."%"])->getFirstItem();
            $profileId = $data->getId();
            foreach($storeIds as $storeId)
            $this->productAction->updateAttributes($productIds, ['betterthat_profile_id'=>$profileId], $storeId);
        }
        return $this;
    }
}
