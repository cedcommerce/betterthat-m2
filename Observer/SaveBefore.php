<?php
namespace Betterthat\Betterthat\Observer;

use function GuzzleHttp\json_decode;

class SaveBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var \Betterthat\Betterthat\Helper\Product
     */
    protected $productHelper;
    /**
     * @var \Betterthat\Betterthat\Helper\Config
     */
    public $config;
    /**
     * @var productRepository
     */
    protected $_productRepository;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action
     */
    protected $productAction;
    /**
     * @var \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory
     */
    protected $profileCollection;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Betterthat\Betterthat\Model\ResourceModel\Profile
     */
    protected $profileResource;
    /**
     * @var \Betterthat\Betterthat\Model\ProfileFactory
     */
    protected $profileModel;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Betterthat\Betterthat\Helper\Product $productHelper
     * @param \Betterthat\Betterthat\Model\ResourceModel\Profile $profileResource
     * @param \Betterthat\Betterthat\Model\ProfileFactory $profileFactory
     * @param \Betterthat\Betterthat\Helper\Config $config
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $productAction
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Betterthat\Betterthat\Helper\Product $productHelper,
        \Betterthat\Betterthat\Model\ResourceModel\Profile $profileResource,
        \Betterthat\Betterthat\Model\ProfileFactory $profileFactory,
        \Betterthat\Betterthat\Helper\Config $config,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction
    ) {
        $this->objectManager = $objectManager;
        $this->productHelper = $productHelper;
        $this->profileResource = $profileResource;
        $this->profileModel = $profileFactory;
        $this->messageManager = $messageManager;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->profileCollection = $collectionFactory;
        $this->productAction = $productAction;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Event\Observer|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->isValid() == "0" || $this->config->isValid() == null) {
            return $observer;
        }
        if ($product = $observer->getEvent()->getProduct()) {
            $storeId = $product->getStoreId();
            try {
                if ($product->dataHasChangedFor('category_ids') && $product->getId()) {
                    $originalCategoryIds = $product->getOrigData('category_ids');
                    if (!$originalCategoryIds) {
                        $originalCategoryIds = [];
                    }
                    $newCategoryIds = $product->getCategoryIds();
                    $categoryIdsAdded = array_diff($newCategoryIds, $originalCategoryIds);
                    if (count($categoryIdsAdded) > 0) {
                        $this->updateItem($categoryIdsAdded, $product, $storeId);
                    }
                }
            } catch (\Exception $e) {
                $e->getMessage();
            }
        }
    }

    /**
     * UpdateItem
     *
     * @param  array $categoryIdsAdded
     * @param  \Magento\Catalog\Model\Product $product
     * @param  mixed $storeId
     * @return void
     * @throws \Exception
     */
    public function updateItem($categoryIdsAdded, $product, $storeId)
    {
        foreach ($categoryIdsAdded as $categoryId) {
            $profileId = null;
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
                $this->productAction
                    ->updateAttributes(
                        [$product->getId()],
                        ['betterthat_profile_id' => $profileId],
                        $storeId
                    );
            }
        }
    }
}
