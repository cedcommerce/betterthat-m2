<?php

/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright Betterthat (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\BetterThat\Model\Source\Config;

class MagentoAttributes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Betterthat\Betterthat\Helper\Category
     * */
    protected $attributeCollection;

    /**
     * @param Category $category
     * */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $attributeCollection
    ) {
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
            $catchAttributes[] = [
                'label' => $attribute->getFrontendLabel(),
                'value' => $attribute->getAttributeCode()
            ];
        }
        return $catchAttributes;
    }
}
