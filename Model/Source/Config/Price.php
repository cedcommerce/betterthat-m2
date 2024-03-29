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

class Price implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => 'Default Magento Price',
                'value' => 'final_price'
            ],
            [
                'label' => 'Increase By Fixed Price',
                'value' => 'plus_fixed'
            ],
            [
                'label' => 'Increase By Fixed Percentage',
                'value' => 'plus_per'
            ],
            [
                'label' => 'Decrease By Fixed Price',
                'value' => 'min_fixed'
            ],
            [
                'label' => 'Decrease By Fixed Percentage',
                'value' => 'min_per'
            ]
        ];
    }
}
