<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 17/1/18
 * Time: 6:33 PM
 */
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

/**
 * Customer account form block
 */
class Js extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Betterthat_Betterthat::profile/attribute/js.phtml';

    /**
     * Core registriee
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    public $request;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * GetProfileId
     *
     * @return mixed
     */
    public function getProfileId()
    {
        $id = $this->request->getParam('id');
        return $id;
    }
}
