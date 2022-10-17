<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product in category grid
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View\Grid;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;

class Product extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registriee
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    /**
     * @var \Betterthat\Betterthat\Model\Source\Profiles
     */
    public $profiles;

    /**
     * @var \Betterthat\Betterthat\Model\Source\Categories
     */
    public $categories;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $_objectManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Betterthat\Betterthat\Model\Source\Profiles $profiles
     * @param \Betterthat\Betterthat\Model\Source\Categories $categories
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Betterthat\Betterthat\Model\Source\Profiles $profiles,
        \Betterthat\Betterthat\Model\Source\Categories $categories,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->profiles = $profiles;
        $this->categories = $categories;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * GetGridUrl
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/products', ['_current' => true]);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('Betterthat_profile_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * AddColumnFilterToCollection
     *
     * @param  Column $column
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_profile') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * GetSelectedProducts
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if ($products === null) {
            $products = $this->getProfile()->getProductsPosition();
            return array_keys($products);
        }
        return $products;
    }

    /**
     * GetProfile
     *
     * @return array|null
     */
    public function getProfile()
    {
        return $this->_coreRegistry->registry('Betterthat_profile');
    }

    /**
     * PrepareCollection
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareCollection()
    {
        //@TODO update get category dynamically
        if ($this->getProfile()->getId()) {
            $this->setDefaultFilter(['in_profile' => $this->getProfile()->getId()]);
        }
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'Betterthat_profile_id'
        )->addAttributeToSelect(
            'price'
        );
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $collection->addStoreFilter($storeId);
        }
        $this->setCollection($collection);

        if ($this->getProfile()->getProductsReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
        }

        return parent::_prepareCollection();
    }

    /**
     * PrepareColumns
     *
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        if (!$this->getProfile()->getProductsReadonly()) {
            $this->addColumn(
                'in_profile',
                [
                    'type' => 'checkbox',
                    'name' => 'in_profile',
                    'values' => $this->_getSelectedProducts(),
                    'index' => 'entity_id',
                    'header_css_class' => 'col-select col-massaction',
                    'column_css_class' => 'col-select col-massaction'
                ]
            );
        }
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'Betterthat_profile_id',
            [
                'header' => __('Profile'),
                'type' => 'options',
                'options' => $this->profiles->getOptionArray(),
                'index' => 'Betterthat_profile_id'

            ]
        );

        $this->addColumn(
            'category_ids',
            [
                'header' => __('Store Category'),
                'type' => 'options',
                'options' => $this->categories->getOptionArray(),
                'index' => 'category_ids',
                'renderer' => \Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View\Grid\Category::class,
                'filter_condition_callback' => [$this, 'filterCallback'],
            ]
        );
        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
        $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'filter' => false,
                'currency_code' => (string)$this->_scopeConfig->getValue(
                    \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'index' => 'price',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * FilterCallBack
     *
     * @param object $collection
     * @param object $column
     * @return mixed
     */
    public function filterCallback($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        $_category = $this->_objectManager->create(\Magento\Catalog\Model\Category::class)
            ->load($value);
        $collection->addCategoryFilter($_category);
        return $collection;
    }
}
