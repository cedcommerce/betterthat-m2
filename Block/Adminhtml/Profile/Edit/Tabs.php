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

namespace Ced\Betterthat\Block\Adminhtml\Profile\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    public function getAttributeTabBlock()
    {
        return 'Ced\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Info';
    }

    /**
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();

        $this->setId('profile_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Profile Information'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'info',
            [
                'label' => __('Profile info'),
                'title' => __('Profile Info'),
                'content' => $this->getLayout()
                    ->createBlock('Ced\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Info')->toHtml(),
                'active' => true
            ]
        );

        $this->addTab(
            'mapping',
            [
                'label' => __('Category & Attribute'),
                'title' => __('Category $ Attribute'),
                'content' => $this->getLayout()
                    ->createBlock('Ced\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Mapping', 'mapping')
                    ->toHtml(),
            ]
        );

        $this->addTab(
            '_magento_category',
            [
                'label' => __('Magento Category Mapping'),
                'title' => __('Magento Category Mapping'),
                'content' => $this->getLayout()
                    ->createBlock('Ced\Betterthat\Block\Adminhtml\Profile\Edit\Tab\MagentoCategory')
                    ->toHtml(),
            ]
        );

        return parent::_beforeToHtml();
    }
}
