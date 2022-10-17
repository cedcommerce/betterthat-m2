<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 30/1/18
 * Time: 11:35 AM
 */

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View\Grid;

class Category extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->productFactory = $productFactory;
    }

    /**
     * Render
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $product = $this->productFactory->create()->load($row->getEntityId());
        $cats = $product->getCategoryIds();
        $allCats = '';
        foreach ($cats as $key => $cat) {
            $_category = $this->categoryFactory->create()
                ->load($cat);
            $allCats.= $_category->getName();
            if ($key < count($cats)-1) {
                $allCats.= ',<br />';
            }
        }
        return $allCats;
    }
}
