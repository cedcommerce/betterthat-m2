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
 * @copyright Copyright BETTERTHAT (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Block\Adminhtml\Product;

class Validate extends \Magento\Backend\Block\Widget\Container
{
    /**
     * Registriee
     *
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * Product Ids
     *
     * @var $productids
     */
    public $ids;
    /**
     * Object Manger
     *
     * @var \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public $objectManager;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Registry $registry,
        $data = []
    ) {
        $this->objectManager = $objectManager;
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->ids = $this->registry->registry('productids');
        $this->_getAddButtonOptions();
    }

    /**
     * GetAddButtonOptions
     *
     * @return void
     */
    public function _getAddButtonOptions()
    {
        $buttonOptions = [
            'label' => __('Back'),
            'class' => 'action-secondary',
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
        ];
        $this->buttonList->add('add', $buttonOptions);

        $buttonOptions = [
            'label' => __('Export'),
            'class' => 'action-primary',
            'onclick' => "setLocation('" . $this->_getExportUrl() . "')"
        ];
        $this->buttonList->add('export', $buttonOptions);
    }

    /**
     * GetCreateUrl
     *
     * @return string
     */
    public function _getCreateUrl()
    {
        return $this->getUrl(
            'betterthat/product/index'
        );
    }

    /**
     * GetExportUrl
     *
     * @return string
     */
    public function _getExportUrl()
    {
        return $this->getUrl(
            'betterthat/product/export'
        );
    }

    /**
     * GetAjaxUrl
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('betterthat/product/validate');
    }
}
