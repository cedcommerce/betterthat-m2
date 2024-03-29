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

namespace Ced\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Attribute;

/**
 * Rolesedit Tab Display Block.
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Attributes extends \Magento\Backend\Block\Widget implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{

    /**
     * @var string
     */
    public $_template = 'Ced_Betterthat::profile/attribute/attributes.phtml';


    public $_objectManager;

    public $_coreRegistry;

    public $profile;

    public $category;

    public $_BetterthatAttribute;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Registry $registry,
        \Ced\Betterthat\Helper\Category $category,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_coreRegistry = $registry;
        $this->category = $category;
        $this->profile = $this->_coreRegistry->registry('current_profile');

        parent::__construct($context, $data);
    }

    /**
     * Retrieve 'add group price item' button HTML
     *
     * @return string
     */
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function getBetterthatAttributes()
    {
        // For AJAX
        $this->_BetterthatAttribute = $this->getAttributes();
        if (isset($this->_BetterthatAttribute) and !empty($this->_BetterthatAttribute)) {
            return $this->_BetterthatAttribute;
        }

        // For Profile Saved
        $categoryIds = json_decode($this->profile->getProfileCategories(), true);

        $params = [
            'category_ids' => $categoryIds,
            'isMandatory' => 1
        ];
        $requiredAttributes = $this->category->getAttributes($params);

        $params = [
            'category_ids' => $categoryIds,
            'isMandatory' => 0
        ];
        $optionalAttributes = $this->category->getAttributes($params);

        $this->_BetterthatAttribute[] = [
            'label' => __('Required Attributes'),
            'value' => $requiredAttributes
        ];


        $this->_BetterthatAttribute[] = [
            'label' => __('Optional Attributes'),
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
            'Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection'
        )
            ->getItems();

        $mattributecode = '--please select--';
        $magentoattributeCodeArray[''] = $mattributecode;
        foreach ($attributes as $attribute) {
            $magentoattributeCodeArray[$attribute->getAttributecode()] = $attribute->getFrontendLabel();
        }

        return $magentoattributeCodeArray;
    }

    public function getMappedAttribute()
    {
        $data = $this->_BetterthatAttribute[0]['value'];
        if ($this->profile && $this->profile->getId() > 0) {
            $requiredAttributes = json_decode($this->profile->getProfileRequiredAttributes(), true);
            foreach ($requiredAttributes as &$attribute) {
                $attribute['options'] = json_decode($attribute['options'], true);
            }
            $optionalAttributes = json_decode($this->profile->getProfileOptionalAttributes(), true);
            foreach ($optionalAttributes as &$attribute) {
                $attribute['options'] = json_decode($attribute['options'], true);
            }
            $data = array_merge($requiredAttributes, $optionalAttributes);
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

    /**
     * Prepare global layout
     * Add "Add tier" button to layout
     *
     * @return $this
     */
    public function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'label' => __('Add Attribute'),
                'onclick' => 'return BetterthatAttributeControl.addItem()',
                'class' => 'add'
            ]
        );

        $button->setName('add_required_item_button');

        $this->setChild('add_button', $button);

        return parent::_prepareLayout();
    }
}
