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
     * @param \Magento\Framework\Registry $registry
     * @param \Betterthat\Betterthat\Helper\Product $product
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Betterthat\Betterthat\Helper\Product $product,
    ) {
        $this->registry = $registry;
        $this->product = $product;

    }

    /**
     * @param \Magento\Catalog\Model\Product $subject
     * @param $result
     * @return void
     */
    public function afterExecute(\Magento\InventoryCatalog\Model\SourceItemsProcessor $subject, $result)
    {
        $productId = $this->registry->registry('changed_product_id');
        $this->product->updatePriceInventory([$productId]);
        return $result;
    }
}
