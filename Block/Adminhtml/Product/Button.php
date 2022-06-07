<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Block\Adminhtml\Product;

use Magento\Backend\Block\Widget\Container;

/**
 * Class Button
 *
 * @package Ced\Betterthat\Block\Adminhtml\Product
 */
class Button extends Container
{
    /**
     * Retrieve options for 'Add Product' split button
     *
     * @return array
     */
    public function _getAddProductButtonOptions()
    {
        $splitButtonOptions = [];

        $splitButtonOptions['massimport'] = [
            'label' => __('Bulk Product Upload'),
            'onclick' => "setLocation('" . $this->getUrl(
                'betterthat/product/additems'
            ) . "')",
            'default' => true,
        ];

        $splitButtonOptions['sync_price_inv'] = [
            'label' => __('Sync Inventory And Price'),
            'onclick' => "setLocation('" . $this->getUrl(
                'betterthat/product/sync'
            ) . "')",
            'default' => false,
        ];
        return $splitButtonOptions;
    }
}
