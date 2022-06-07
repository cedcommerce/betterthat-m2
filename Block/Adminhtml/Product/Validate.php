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
 * @copyright Copyright CEDCOMMERCE (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Block\Adminhtml\Product;

class Validate extends \Magento\Backend\Block\Widget\Container
{
    /**
     * Registry
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
     * BatchUpload constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Backend\Block\Widget\Context     $context
     * @param \Magento\Framework\Registry               $registry
     * @param array                                     $data
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
    public function _getCreateUrl()
    {
        return $this->getUrl(
            'betterthat/product/index'
        );
    }

    public function _getExportUrl()
    {
        return $this->getUrl(
            'betterthat/product/export'
        );
    }

    public function getAjaxUrl()
    {
        return $this->getUrl('betterthat/product/validate');
    }
}
