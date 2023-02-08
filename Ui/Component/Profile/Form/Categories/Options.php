<?php

namespace Betterthat\Betterthat\Ui\Component\Profile\Form\Categories;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var \Betterthat\Betterthat\Helper\Category
     */
    public $category;

    /**
     * @param \Betterthat\Betterthat\Helper\Category $category
     */
    public function __construct(
        \Betterthat\Betterthat\Helper\Category $category
    ) {
        $this->category = $category;
    }

    /**
     * ToOptionArray
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->category->getCategoriesTree();
    }
}
