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

namespace Ced\Betterthat\Model\Source\Config;

class Attributes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Ced\Betterthat\Helper\Category
     * */
    protected $category;

    /**
     * @param Category $category
     * */
    public function __construct(
        \Ced\Betterthat\Helper\Category $category
    )
    {
        $this->category = $category;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $attributes = $BetterthatAttributes = [];
        $attributes = $this->category->getAllAttributes();
        if(isset($attributes) && is_array($attributes)) {
            $attributes = array_column($attributes, 'label', 'code');
        }
        foreach ($attributes as $attributeCode => $attributeLabel) {
            $BetterthatAttributes[] = array(
                'label' => $attributeLabel,
                'value' => $attributeCode
            );
        }
        return $BetterthatAttributes;
    }
}
