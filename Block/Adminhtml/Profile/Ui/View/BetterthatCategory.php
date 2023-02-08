<?php

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View;

class BetterthatCategory extends \Magento\Backend\Block\Template
{
     /**
      * @var string
      */
    public $_template = 'Betterthat_Betterthat::profile/category/betterthat_category.phtml';
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $_objectManager;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    public $request;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
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
        return $this->_objectManager->get(\Magento\Backend\Model\UrlInterface::class)
            ->getUrl('Betterthat/category/fetch');
    }

    /**
     * GetCategoryAttributeUrl
     *
     * @return mixed
     */
    public function getCategoryAttributeUrl()
    {
        return $this->_objectManager->get(\Magento\Backend\Model\UrlInterface::class)
            ->getUrl('Betterthat/profile/updateCategoryAttributes');
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

    /**
     * PrepareLayout
     *
     * @return BetterthatCategory|void
     */
    protected function _prepareLayout()
    {
        $this->addChild(
            'Betterthat_attributes',
            \Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View\AttributeMapping::class
        );
    }
}
