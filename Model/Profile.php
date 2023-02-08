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


namespace Betterthat\Betterthat\Model;

class Profile extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public $product;

    /**
     * Profile products flipped
     *
     * @var array
     */
    public $productIds = [];
    /**
     * @var objectManager
     */
    public $objectManager;

    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Betterthat\Betterthat\Model\ResourceModel\Profile::class);
    }

    /**
     * GetProductsReadonly
     *
     * @return array
     */
    public function getProductsReadonly()
    {
        return [];
    }

    /**
     * GetProductsPosition
     *
     * @return array|int[]|string[]
     */
    public function getProductsPosition()
    {
        if (!isset($this->objectManager)) {
            $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        }

        if (!isset($this->product)) {
            $this->product = $this->objectManager
                ->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class);
        }

        $id = $this->getId();
        $ids = $this->product->create()
            ->addAttributeToFilter('Betterthat_profile_id', ['eq' => $id])
            ->addAttributeToSelect('entity_id')->getAllIds();
        if (is_array($ids) && !empty($ids)) {
            $this->productIds = array_flip($ids);
        }

        return $this->productIds;
    }

    /**
     * RemoveProducts
     *
     * @param json $values
     * @return void
     */
    public function removeProducts($values)
    {
        if ($values) {
            $values = json_decode($values, true);
        }
        if (!isset($this->objectManager)) {
            $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        }

        if (!isset($this->product)) {
            $this->product = $this->objectManager
                ->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class);
        }

        $id = $this->getId();

        $products = $this->product->create()
            ->addAttributeToFilter('Betterthat_profile_id', ['eq' => $id])
            ->addAttributeToSelect('Betterthat_profile_id');
        $products->addCategoriesFilter(['nin' => $values]);
        $products->addAttributeToFilter('type_id', ['in' => ['simple', 'configurable']]);
        // Removing profile id from already added products
        foreach ($products as $product) {
            $product->setBetterthatProfileId('');
            $product->getResource()
                ->saveAttribute($product, 'Betterthat_profile_id');
        }
    }

    /**
     * AddProducts
     *
     * @param json $values
     * @return void
     */
    public function addProducts($values)
    {
        if ($values) {
            $values = json_decode($values, true);
        }
        if (!isset($this->objectManager)) {
            $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        }

        if (!isset($this->product)) {
            $this->product = $this->objectManager
                ->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class);
        }

        $id = $this->getId();

        $products = $this->product->create()
            ->addAttributeToSelect('betterthat_profile_id');
        $products->addCategoriesFilter(['in' => $values]);
        $products->addAttributeToFilter('type_id', ['in' => ['simple', 'configurable']]);
        foreach ($products as $product) {
            $product->setBetterthatProfileId($id);
            $product->getResource()
                ->saveAttribute($product, 'betterthat_profile_id');
        }
    }

    /**
     * Load entity by attribute
     *
     * @param string|array $field
     * @param null|string|array $value
     * @param string $additionalAttributes
     * @return mixed
     */
    public function loadByField($field, $value, $additionalAttributes = '*')
    {
        $collection = $this->getResourceCollection()->addFieldToSelect($additionalAttributes);
        if (is_array($field) && is_array($value)) {
            foreach ($field as $key => $f) {
                if (isset($value[$key])) {
                    $collection->addFieldToFilter($f, $value[$key]);
                }
            }
        } else {
            $collection->addFieldToFilter($field, $value);
        }

        $collection->setCurPage(1)
            ->setPageSize(1);
        foreach ($collection as $object) {
            $this->load($object->getId());
            return $this;
        }
        return $this;
    }
}
