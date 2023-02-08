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

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * GetAttributeTabBlock
     *
     * @return string
     */
    public function getAttributeTabBlock()
    {
        return \Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Info::class;
    }
    /**
     * Construct
     *
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
     * BeforeToHtml
     *
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
                    ->createBlock(\Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Info::class)
                    ->toHtml(),
                'active' => true
            ]
        );
        $this->addTab(
            'mapping',
            [
                'label' => __('Category & Attribute'),
                'title' => __('Category $ Attribute'),
                'content' => $this->getLayout()
                    ->createBlock(\Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Mapping::class, 'mapping')
                    ->toHtml(),
            ]
        );
        $this->addTab(
            '_magento_category',
            [
                'label' => __('Magento Category Mapping'),
                'title' => __('Magento Category Mapping'),
                'content' => $this->getLayout()
                    ->createBlock(\Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab\MagentoCategory::class)
                    ->toHtml(),
            ]
        );
        return parent::_beforeToHtml();
    }
}
