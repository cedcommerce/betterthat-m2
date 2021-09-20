<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\BetterThat\Model\Source\Config;

class MagentoAttributes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Ced\Betterthat\Helper\Category
     * */
    protected $attributeCollection;

    /**
     * @param Category $category
     * */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $attributeCollection
    )
    {
        $this->attributeCollection = $attributeCollection;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $catchAttributes = [];
        $attributes = $this->attributeCollection;
        foreach ($attributes as $attribute) {
            $catchAttributes[] = array(
                'label' => $attribute->getFrontendLabel(),
                'value' => $attribute->getAttributeCode()
            );
        }
        return $catchAttributes;
    }
}
