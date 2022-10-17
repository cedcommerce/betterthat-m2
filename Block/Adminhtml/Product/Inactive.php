<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 9/1/18
 * Time: 1:24 PM
 */

namespace Betterthat\Betterthat\Block\Adminhtml\Product;

class Inactive extends \Magento\Backend\Block\Widget\Container
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
     * BatchUpload constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Backend\Block\Widget\Context     $context
     * @param \Magento\Framework\Registry               $registry
     * @param array                                     $data
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Backend\Block\Widget\Context $context,
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
        $backButtonOptions = [
            'label' => __('Back'),
            'class' => 'action-secondary',
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
        ];
        $this->buttonList->add('add', $backButtonOptions);
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
     * GetAjaxUrl
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('betterthat/product/inactive');
    }
}
