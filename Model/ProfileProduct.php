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

class ProfileProduct extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Betterthat\Betterthat\Model\ResourceModel\ProfileProduct::class);
    }

    /**
     * @return $this
     */

    public function update()
    {
        $this->getResource()->update($this);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductsCollection()
    {
        return $this->getResource(\Betterthat\Betterthat\Model\ResourceModel\ProfileProduct\Collection::class);
    }

    /**
     * getting group vendors
     */
    public function getProfileProducts($profileId)
    {
        return $this->getResource()->getProfileProducts($profileId);
    }

    public function deleteFromProfile($productId)
    {
        $this->_getResource()->deleteFromProfile($productId);
        return $this;
    }

    public function deleteProducts($productIds)
    {
        $this->_getResource()->deleteProducts($productIds);
        return $this;
    }

    public function addProducts($productIds, $profileId)
    {
        $this->_getResource()->addProducts($productIds, $profileId);
        return $this;
    }

    public function profileProductExists($productId, $profileId)
    {
        $result = $this->_getResource()->profileProductExists($productId, $profileId);
        return (is_array($result) && count($result) > 0) ? true : false;
    }

    /**
     * Load entity by attribute
     *
     * @param  string|array field
     * @param  null|string|array  $value
     * @param  string             $additionalAttributes
     * @return bool|\Betterthat\Betterthat\Model\ProfileProduct
     */
    public function loadByField($field, $value, $additionalAttributes = '*')
    {
        $collection = $this->getResourceCollection()
            ->addFieldToSelect($additionalAttributes);
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
