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

namespace Betterthat\Betterthat\Block\Adminhtml\FailedOrder;

class SyncStatus extends \Magento\Backend\Block\Widget\Container
{

    /**
     * Object Manger
     *
     * @var \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public $objectManager;

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
        $this->_getAddButtonOptions();
        $this->registry = $registry;
        $this->ids = $this->registry->registry('orderids');
    }

    public function _getAddButtonOptions()
    {
        $splitButtonOptions = [
            'label' => __('Back'),
            'class' => 'action-secondary',
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
        ];
        $this->buttonList->add('add', $splitButtonOptions);
    }

    public function _getCreateUrl()
    {
        return $this->getUrl(
            'Betterthat/failedorder/index'
        );
    }

    public function getAjaxUrl()
    {
        return $this->getUrl('Betterthat/failedorder/syncstatus');
    }
}
