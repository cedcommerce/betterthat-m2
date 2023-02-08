<?php

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View;

class BetterthatCategoryMapping extends \Magento\Backend\Block\Template
{
    /**
     * Templates
     *
     * @var string
     */
    public $_template = 'Betterthat_Betterthat::profile/category/Betterthat_category_mapping.phtml';
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $_objectManager;
    /**
     * @var \Magento\Framework\Registry
     */
    public $_coreRegistry;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    public $request;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Registry $registry
     * @param \Betterthat\Betterthat\Model\Profile $profile
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Betterthat\Betterthat\Model\Profile $profile,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_coreRegistry = $registry;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * GetCurrentProfile
     *
     * @return mixed
     */
    public function getCurrentProfile()
    {
        return $this->request->getParam('id', 0);
    }

    /**
     * GetBetterthatCategoryUrl
     *
     * @return mixed
     */
    public function getBetterthatCategoryUrl()
    {
        return $this->_objectManager
            ->get(\Magento\Backend\Model\UrlInterface::class)
            ->getUrl('betterthat/category/fetch');
    }
    /**
     * Render form element as HTML
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }
}
