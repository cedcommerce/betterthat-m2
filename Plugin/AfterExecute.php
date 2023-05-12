<?php

namespace Betterthat\Betterthat\Plugin;

class AfterExecute
{
    /**
     * @var \Betterthat\Betterthat\Helper\Product
     */
    protected $product;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Betterthat\Betterthat\Helper\Logger
     */
    protected $logger;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     * @param \Betterthat\Betterthat\Helper\Product $product
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Betterthat\Betterthat\Helper\Product $product
    ) {
        $this->registry = $registry;
        $this->logger = $logger;
        $this->product = $product;
    }

    /**
     * AfterExecute
     *
     * @param \Magento\InventoryCatalog\Model\SourceItemsProcessor $subject
     * @param array $result
     * @return mixed
     */
    public function afterExecute(\Magento\InventoryCatalog\Model\SourceItemsProcessor $subject, $result)
    {
        $productId = $this->registry->registry('changed_product_id');
        $this->product->updatePriceInventory([$productId]);
        return $result;
    }
}