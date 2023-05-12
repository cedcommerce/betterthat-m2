<?php

namespace Betterthat\Betterthat\Observer;

use function GuzzleHttp\json_decode;

class SaveCategory implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var API
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
     * @var \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory
     */
    public $profileCollection;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action
     */
    public $productAction;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $productAction
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collectionFactory
     * @param \Betterthat\Betterthat\Helper\Config $config
     */
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

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|\Magento\Framework\Event\Observer|void
     * @throws \Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->isValid() == "0" || $this->config->isValid() == null) {
            return $observer;
        }
        //$this->logger->info('category_save', ['path' => __METHOD__, 'category_data' => 'Category Observer Working']);
        $productIds = $observer->getEvent()->getProductIds();
        $categoryId = $observer->getEvent()->getCategory()->getId();
        $profileId = '';
        if ($productIds) {
            $storeIds = array_keys($this->storeManager->getStores());
            $data = $this->profileCollection
                ->create()
                ->addFieldToFilter("magento_category", ["like" => "%" . $categoryId . "%"]);
            if (count($data) > 0) {
                foreach ($data as $item) {
                    $magento_cat = json_decode($item->getMagentoCategory());
                    if (in_array($categoryId, $magento_cat)) {
                        $profileId = $item->getId();
                    }
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
