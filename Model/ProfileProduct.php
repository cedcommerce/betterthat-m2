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
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Betterthat\Betterthat\Model\ResourceModel\ProfileProduct::class);
    }

    /**
     * Update
     *
     * @return $this
     */
    public function update()
    {
        $this->getResource()->update($this);
        return $this;
    }

    /**
     * GetProductsCollection
     *
     * @return \Magento\Framework\Model\ResourceModel\AbstractResource|\Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    public function getProductsCollection()
    {
        return $this->getResource(\Betterthat\Betterthat\Model\ResourceModel\ProfileProduct\Collection::class);
    }

    /**
     * GetProfileProducts
     *
     * @param int $profileId
     * @return mixed
     */
    public function getProfileProducts($profileId)
    {
        return $this->getResource()->getProfileProducts($profileId);
    }

    /**
     * DeleteFromProfile
     *
     * @param int $productId
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteFromProfile($productId)
    {
        $this->_getResource()->deleteFromProfile($productId);
        return $this;
    }

    /**
     * DeleteProducts
     *
     * @param array $productIds
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteProducts($productIds)
    {
        $this->_getResource()->deleteProducts($productIds);
        return $this;
    }

    /**
     * AddProducts
     *
     * @param array $productIds
     * @param string $profileId
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addProducts($productIds, $profileId)
    {
        $this->_getResource()->addProducts($productIds, $profileId);
        return $this;
    }

    /**
     * ProfileProductExists
     *
     * @param int $productId
     * @param int $profileId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function profileProductExists($productId, $profileId)
    {
        $result = $this->_getResource()->profileProductExists($productId, $profileId);
        return (is_array($result) && count($result) > 0) ? true : false;
    }

    /**
     * LoadByField
     *
     * @param string $field
     * @param string $value
     * @param array $additionalAttributes
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
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
