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

namespace Betterthat\Betterthat\Helper;

use Magento\Framework\App\Helper\Context;

class Profile extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const REQUIRED_ATTRIBUTES = [
        "title",
        "short-desc",
        "standard-price",
        "brand",
        "shipping-length",
        "shipping-width",
        "shipping-height",
        "shipping-weight",
        "offer-condition/condition",
        "item-id",
        "image-url",
        "standard-price",
        "upc",
        "model-number",
        "long-desc",
    ];

    public const OPTIONAL_ATTRIBUTES = [
        "mature-content",
        "local-marketplace-flags/is-restricted",
        "local-marketplace-flags/perishable",
        "local-marketplace-flags/requires-refrigeration",
        "local-marketplace-flags/requires-freezing",
        "local-marketplace-flags/contains-alcohol",
        "local-marketplace-flags/contains-tobacco",
        "no-warranty-available"
    ];

    public const CONF_REQUIRED_ATTRIBUTES = [
        "title",
        "brand",
        "short-desc",
        "long-desc",
        "model-number"
    ];

    public const DEFAULT_ATTRIBUTES = [
        'shipping-length', //set 1.0
        'shipping-width', //set 1.0
        'shipping-height', //set 1.0
        'offer-condition/condition' //set NEW
    ];

    /**
     * @var profile
     */
    public $profile;

    /**
     * @var profileCode
     */
    public $profileCode;

    /**
     * @var $categories
     */
    public $categories;

    /**
     * @var attributes
     */
    public $attributes;

    /**
     * @var array
     */
    public $BetterthatAttribute = [];

    /**
     * Json Parser
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;
    /**
     * @var array
     */
    public $requiredAttributes = [];
    /**
     * @var array
     */
    public $optionalAttributes = [];
    /**
     * @var array
     */
    public $variantAttributes = [];
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    public $profileProduct;
    /**
     * @var \Betterthat\Betterthat\Model\ProfileFactory
     */
    public $profileFactory;
    /**
     * @var Cache
     */
    public $BetterthatCache;

    /**
     * @param Context $context
     * @param \Magento\Framework\Json\Helper\Data $json
     * @param \Magento\Catalog\Model\ProductFactory $profileProduct
     * @param \Betterthat\Betterthat\Model\ProfileFactory $profile
     * @param Cache $cache
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Json\Helper\Data $json,
        \Magento\Catalog\Model\ProductFactory $profileProduct,
        \Betterthat\Betterthat\Model\ProfileFactory $profile,
        \Betterthat\Betterthat\Helper\Cache $cache
    ) {
        $this->json = $json;
        $this->profileProduct = $profileProduct;
        $this->BetterthatCache = $cache;
        $this->profileFactory = $profile;
        parent::__construct($context);
    }

    /**
     * Get Profile
     *
     * @param  null|string $productId
     * @param  null|string $profileId
     * @return \Betterthat\Betterthat\Helper\Profile|null
     */
    public function getProfile($productId = null, $profileId = null)
    {
        if (empty($profileId) || !is_numeric($profileId)) {
            $profileId = $this->profileProduct->create()->load($productId, 'product_id')
                ->getBetterthatProfileId();
        }
        $this->profile = $this->BetterthatCache
                            ->getValue(\Betterthat\Betterthat\Helper\Cache::PROFILE_CACHE_KEY . $profileId);
         $this->setProfile($profileId);
        return $this;
    }

    /**
     * Set Profile
     *
     * @param  string $profileId
     * @return boolean
     */
    public function setProfile($profileId = null)
    {
        if (isset($profileId)) {
            $this->profile  = $this->profileFactory->create()->load($profileId)->getData();
            if (isset($this->profile) && is_array($this->profile)) {
                if (isset($this->profile['profile_categories'])
                    && $this->profile['profile_categories']) {
                    $this->profile['profile_categories'] =
                        $this->json->jsonDecode($this->profile['profile_categories']);
                } else {
                    $this->profile['profile_categories'] =[];
                }
                if (isset($this->profile['profile_required_attributes'])
                    && $this->profile['profile_required_attributes']) {
                    $requiredAttributes = $this->json
                        ->jsonDecode($this->profile['profile_required_attributes']);
                } else {
                    $requiredAttributes =[];
                }
                foreach ($requiredAttributes as &$attribute) {
                    $validOptionsModified = $optionsModified = [];
                    try {
                           $options = $this->json->jsonDecode($attribute['option_mapping']);
                           $validOptions = $this->json->jsonDecode($attribute['options']);
                    } catch (\Exception $e) {
                        $options = [];
                        $validOptions = [];
                    }
                    if (count($options)) {
                        foreach ($options as $optionName => $optionValue) {
                            $optionsModified[$optionValue] = $optionName;
                        }
                    }
                    if (count($validOptions) > 0) {
                        foreach ($validOptions as $optionName => $optionValue) {
                            $validOptionsModified[$optionName] = $optionValue;
                        }
                    }
                    $attribute['options'] = $validOptionsModified ;
                    $attribute['option_mapping'] = $optionsModified ;
                }
                $this->profile['profile_required_attributes'] =  $requiredAttributes;
                //$this->json->jsonEncode($requiredAttributes);

                if (isset($this->profile['profile_optional_attributes'])
                    && $this->profile['profile_optional_attributes']) {
                    $optionalAttributes = $this->json
                        ->jsonDecode($this->profile['profile_optional_attributes']);
                } else {
                    $optionalAttributes =[];
                }

                foreach ($optionalAttributes as &$attribute) {
                    $validOptionsModified = $optionsModified = [];
                    if (isset($attribute['option_mapping']) && $attribute['option_mapping']) {
                        try {
                            $options = $this->json->jsonDecode($attribute['option_mapping']);
                            $validOptions = $this->json->jsonDecode($attribute['options']);
                        } catch (\Exception $e) {
                            $options = [];
                            $validOptions = [];
                        }
                    } else {
                        $options =[];
                    }
                    if (count($options)) {
                        foreach ($options as $optionName => $optionValue) {
                            $optionsModified[$optionValue] = $optionName;
                        }
                    }
                    if (count($validOptions) > 0) {
                        foreach ($validOptions as $optionName => $optionValue) {
                            $validOptionsModified[$optionName] = $optionValue;
                        }
                    }
                    $attribute['options'] = $validOptionsModified ;
                    $attribute['option_mapping'] = $optionsModified;
                }
                $this->profile['profile_optional_attributes'] =  $optionalAttributes;
                //$attributes = array_merge($requiredAttributes, $optionalAttributes);
                $attributes = $requiredAttributes + $optionalAttributes;
                $this->profile['profile_attributes'] = $attributes;
                $this->BetterthatCache
                    ->setValue(\Betterthat\Betterthat\Helper\Cache::PROFILE_CACHE_KEY . $profileId, $this->profile);
                return true;
            }
        }
        return false;
    }

    /**
     * GetAttribute
     *
     * @param string $attributeId
     * @return array
     */
    public function getAttribute($attributeId)
    {
        if (isset($this->profile['profile_attributes'][$attributeId])) {
            return $this->profile['profile_attributes'][$attributeId];
        }
        return [];
    }

    /**
     * GetMappedAttribute
     *
     * @param string $attributeId
     * @return false
     */

    public function getMappedAttribute($attributeId)
    {
        if (isset($this->profile['profile_attributes'][$attributeId]['magento_attribute_code'])) {
            return $this->profile['profile_attributes'][$attributeId]['magento_attribute_code'];
        }
        return false;
    }

    /**
     * GetAttributes
     *
     * @param string $type
     * @return array
     */
    public function getAttributes($type = null)
    {
        if (isset($this->profile['profile_attributes'])) {
            return $this->profile['profile_attributes'];
        }
        return [];
    }

    /**
     * GetRequiredAttributes
     *
     * @param string $type
     * @return array
     */
    public function getRequiredAttributes($type = null)
    {
        if (isset($this->profile['profile_required_attributes'])) {
            if (isset($type) && !empty($type)) {
                $attributes = [];
                foreach ($this->profile['profile_required_attributes'] as $id => $attribute) {
                        $attributes[$id] = $attribute;
                }
                return $attributes;
            }
            return $this->profile['profile_required_attributes'];
        }
        return [];
    }

    /**
     * GetBetterThatCategory
     *
     * @return string
     */
    public function getBetterThatCategory()
    {
        if (isset($this->profile['betterthat_categories'])) {
            return $this->profile['betterthat_categories'];
        }
        return '';
    }

    /**
     * Get Profile Category
     *
     * @return boolean|string
     */
    public function getId()
    {
        if (isset($this->profile['id'])) {
            return $this->profile['id'];
        }
        return false;
    }

    /**
     * Get Profile
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->profile)) {
            return $this->profile;
        }
        return [];
    }

     /**
      * Get All Products Ids
      *
      * @param  string $productId
      * @return mixed
      */
    public function getProducts($productId = null)
    {
        $productIds = [];
        if (isset($productId)) {
            $profile = $this->getProfile(null, $productId);
            $profileId = $profile->getId();
            if (isset($profileId) && !empty($profileId)) {
                $productIds = $this->profileProduct->create()->getCollection()
                    ->addFieldToFilter('profile_id', ['eq' => $profileId])
                    ->getColumnValues('product_id');
            }
        } else {
            $productIds = $this->profileProduct
                ->create()
                ->getCollection()
                ->getColumnValues('product_id');
        }
        return $productIds;
    }
}
