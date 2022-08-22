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

namespace Betterthat\Betterthat\Model\Source\Config;

class Attributes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Betterthat\Betterthat\Helper\Category
     * */
    protected $category;

    /**
     * @param Category $category
     * */
    public function __construct(
        \Betterthat\Betterthat\Helper\Category $category
    ) {
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
        if (isset($attributes) && is_array($attributes)) {
            $attributes = array_column($attributes, 'label', 'code');
        }
        foreach ($attributes as $attributeCode => $attributeLabel) {
            $BetterthatAttributes[] = [
                'label' => $attributeLabel,
                'value' => $attributeCode
            ];
        }
        return $BetterthatAttributes;
    }
}
