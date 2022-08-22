<?php
/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Block\Adminhtml\Product;

use Magento\Backend\Block\Widget\Container;

/**
 * Class Button
 *
 * @package Betterthat\Betterthat\Block\Adminhtml\Product
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
