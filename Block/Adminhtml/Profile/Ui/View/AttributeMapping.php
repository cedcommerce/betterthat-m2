<?php

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View;

class AttributeMapping extends \Magento\Backend\Block\Template
{
     /**
      * @var string
      */
    public $_template = 'Betterthat_Betterthat::profile/attribute/attributes.phtml';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $_objectManager;
    /**
     * @var \Magento\Framework\Registry
     */
    public $_coreRegistry;
    /**
     * @var \Betterthat\Betterthat\Model\Profile
     */
    public $profile;
    /**
     * @var \Betterthat\Betterthat\Helper\Category
     */
    public $category;
    /**
     * @var Btattribue
     */
    public $_BetterthatAttribute;

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
     * @param \Betterthat\Betterthat\Helper\Category $category
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Betterthat\Betterthat\Model\Profile $profile,
        \Betterthat\Betterthat\Helper\Category $category,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_coreRegistry = $registry;
        $this->category = $category;
        $this->request = $request;
        $this->session =  $context->getSession();
        $id = $this->request->getParam('current_profile_id');
        $this->profile = $profile->load($id);
        parent::__construct($context, $data);
    }

    /**
     * GetAddButtonHtml
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAddButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'label' => __('Add Attribute'),
                'onclick' => 'return betterthatAttributeControl.addItem()',
                'class' => 'add'
            ]
        );

        $button->setName('betterthat_add_attribute_mapping_button');
        return $button->toHtml();
    }

    /**
     * GetBetterthatAttributes
     *
     * @return mixed
     */
    public function getBetterthatAttributes()
    {
        // For AJAX
        $this->_BetterthatAttribute = $this->getAttributes();

        if (isset($this->_BetterthatAttribute) && !empty($this->_BetterthatAttribute)) {
            return $this->_BetterthatAttribute;
        }
        // For Profile Saved
        $categoryId = $this->profile->getProfileCategory();
        $params = [
            'hierarchy' => '',
            'isMandatory' => 1
        ];
        $requiredAttributes = $this->category->getAttributes($params);
        $params = [
            'hierarchy' => '',
            'isMandatory' => 0
        ];
        $optionalAttributes = $this->category->getAttributes($params);
        $this->_BetterthatAttribute[] = [
            'label' => 'Required Attributes',
            'value' => $requiredAttributes
        ];
        $this->_BetterthatAttribute[] = [
            'label' => 'Optional Attributes',
            'value' => $optionalAttributes
        ];
        return $this->_BetterthatAttribute;
    }

    /**
     * Retrieve magento attributes
     *
     * @param  int|null $groupId return name by customer group id
     * @return array|string
     */
    public function getMagentoAttributes()
    {
        $attributes = $this->_objectManager->create(
            \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection::class
        )->getItems();

        $mattributecode = '--Please Select--';
        /*$magentoattributeCodeArray[''] = $mattributecode;
        $magentoattributeCodeArray['default'] = '--Default Value--';*/
        $magentoattributeCodeArray[''] =
            [
                'attribute_code' => $mattributecode,
                'attribute_type' => '',
                'input_type' => '',
                'option_values' => ''
            ];
        $magentoattributeCodeArray['default'] =
            [
                'attribute_code' =>"-- Set Default Value --",
                'attribute_type' => '',
                'input_type' => '',
                'option_values' => ''
            ];
        $magentoattributeCodeArray['entity_id'] =
            [
                'attribute_code' =>"Product Id",
                'attribute_type' => '',
                'input_type' => '',
                'option_values' => ''
            ];
        foreach ($attributes as $attribute) {
            $type = "";
            $optionValues = "";
            $attributeOptions = $attribute->getSource()->getAllOptions(false);
            if (!empty($attributeOptions) && is_array($attributeOptions)) {
                $type = " [ select ]";
                foreach ($attributeOptions as &$option) {
                    if (isset($option['label']) && is_object($option['label'])) {
                        $option['label'] = $option['label']->getText();
                    }
                }
                $attributeOptions = str_replace(
                    '\'',
                    '&#39;',
                    json_encode($attributeOptions)
                );
                $optionValues = $attributeOptions;
            }
            if ($attribute->getFrontendInput() =='select') {
                $magentoattributeCodeArray[$attribute->getAttributecode()] =
                    [
                        'attribute_code' => $attribute->getFrontendLabel() . $type,
                        'attribute_type' => $attribute->getFrontendInput(),
                        'input_type' => 'select',
                        'option_values' => $optionValues,
                    ];
            } else {
                $magentoattributeCodeArray[$attribute->getAttributecode()] =
                    [
                        'attribute_code' => $attribute->getFrontendLabel(),
                        'attribute_type' => $attribute->getFrontendInput(),
                        'input_type' => '',
                        'option_values' => $optionValues,
                    ];
            }
        }
        return $magentoattributeCodeArray;
    }

    /**
     * GetMappedAttribute
     *
     * @return array|mixed
     */
    public function getMappedAttribute()
    {
        $data = $this->_BetterthatAttribute[0]['value'];
        $reqAttrCodes = array_keys($data);
        $optData = $this->_BetterthatAttribute[1]['value'];
        $optAttrCodes = array_keys($optData);
        $requiredAttributes = [];
        $optionalAttributes = [];
        if ($this->profile && $this->profile->getId() && $this->profile->getProfileRequiredAttributes()) {
            $requiredAttributes = json_decode($this->profile->getProfileRequiredAttributes(), true);
            if (is_array($requiredAttributes) && count($requiredAttributes)) {
                foreach ($requiredAttributes as &$attribute) {
                    $attribute['options'] = json_decode($attribute['options'], true);
                    if (!in_array($attribute['name'], $reqAttrCodes)) {
                        unset($requiredAttributes[$attribute['name']]);
                    }
                }
            }

            $optionalAttributes =
                json_decode($this->profile->getProfileOptionalAttributes(), true);
            if (is_array($optionalAttributes) && count($optionalAttributes)) {
                foreach ($optionalAttributes as &$attribute) {
                    $attribute['options'] = json_decode($attribute['options'], true);
                    if (!in_array($attribute['name'], $optAttrCodes)) {
                        unset($optionalAttributes[$attribute['name']]);
                    }
                }
            }
            if (is_array($requiredAttributes) && is_array($optionalAttributes)) {
                $data = $requiredAttributes + $optionalAttributes + $data;
            }
        }
        return $data;
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
