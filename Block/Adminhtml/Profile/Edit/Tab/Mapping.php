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

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab;

use BetterthatSdk\Product;

class Mapping extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var Betterthat
     */
    public $Betterthat;
    /**
     * @var product
     */
    public $product;
    /**
     * @var config
     */
    public $config;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectInterface
     * @param \Betterthat\Betterthat\Helper\Config $config
     * @param \BetterthatSdk\ProductFactory $product
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\ObjectManagerInterface $objectInterface,
        \Betterthat\Betterthat\Helper\Config $config,
        \BetterthatSdk\ProductFactory $product,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_objectManager = $objectInterface;
        $this->product = $product;
        $this->config = $config;
        parent::__construct($context, $registry, $formFactory);
    }

    /**
     * PrepareForm
     *
     * @return Mapping
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $product = $this->product->create(
            [
            'config' => $this->config->getApiConfig()
            ]
        );

        $fieldset = $form->addFieldset('category', ['legend' => __('Betterthat Category Listing')]);

        $catgories = $product->getCategories();

        $parentCategories = [];
        foreach ($catgories as $catgory) {
            $parentCategories[] = ['value' => $catgory['categoryId'], 'label' =>  $catgory['name']];
        }

        $fieldset->addField(
            'profile_category_1',
            'select',
            [
                'name' => 'profile_category_1',
                'label' => __('Parent Category'),
                'title' => __('Parent Category'),
                'class' => 'level-category',
                'required' => true,
                'style' => 'width: 100%',
                'values' => $parentCategories,
            ]
        );

        $fieldset->addField(
            'profile_category_2',
            'select',
            [
                'name' => 'profile_category_2',
                'label' => __('Category Level 1'),
                'title' => __('Category Level 1'),
                'class' => 'level-1-category',
                'required' => false,
                'style' => 'width: 100%',
                'values' => []
            ]
        );
        $fieldset->addField(
            'profile_category_3',
            'select',
            [
                'name' => 'profile_category_3',
                'label' => __('Category  Level 2'),
                'title' => __('Category  Level 2'),
                'class' => 'level-2-category',
                'required' => false,
                'style' => 'width: 100%',
                'values' => []
            ]
        );

        $fieldset->addField(
            'profile_category_4',
            'select',
            [
                'name' => 'profile_category_4',
                'label' => __('Category  Level 3'),
                'title' => __('Category  Level 3'),
                'class' => 'level-3-category',
                'required' => false,
                'style' => 'width: 100%',
                'values' => []
            ]
        );

        $fieldset->addField(
            'profile_category_5',
            'select',
            [
                'name' => 'profile_category_5',
                'label' => __('Category  Level 4'),
                'title' => __('Category  Level 4'),
                'class' => 'level-4-category',
                'required' => false,
                'style' => 'width: 100%',
                'values' => []
            ]
        );

        $fieldset->addField(
            'profile_category_6',
            'select',
            [
                'name' => 'profile_category_6',
                'label' => __('Category  Level 5'),
                'title' => __('Category  Level 5'),
                'class' => 'level-4-category',
                'required' => false,
                'style' => 'width: 100%',
                'values' => []
            ]
        );

        $fieldset->addField(
            'category_js',
            'text',
            [
                'label' => __('Category JS Mapping'),
                'class' => 'action',
                'name' => 'category_js_mapping'
            ]
        );

        $locations = $form->getElement('category_js');
        $locations->setRenderer(
            $this->getLayout()
                ->createBlock(\Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Attribute\CategoryJs::class)
        );

        $fieldset = $form->addFieldset(
            'attributes_fieldset',
            [
                'legend' => __('Betterthat Attributes Mapping')
            ]
        );

        $fieldset->addField(
            'attributes',
            'text',
            [
                'label' => __('Attribute Mapping'),
                'class' => 'action',
                'name' => 'required_attribute'
            ]
        );
        $locations = $form->getElement('attributes');
        $locations->setRenderer(
            $this->getLayout()->createBlock(
                \Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Attribute\Attributes::class,
                'Betterthat_attributes'
            )
        );
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
