<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Exception;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    public $objectManager;

    public $config;

    public $product;

    public $categories = [];
    public $categoriesTree = [];

    public $defaultMapping = [
        'title' => 'name',
        'body_html' => 'description',
        'retailer_id' => 'retailer_id',
        'manufacturer' => 'manufacturer',
        'product_shipping_options' => ''
    ];

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        \Ced\Betterthat\Helper\Config $config,
        \BetterthatSdk\ProductFactory $product,
        \Magento\Framework\Filesystem\DirectoryList $directoryList
    ) {
        $this->objectManager = $objectManager;
        $this->product = $product;
        $this->config = $config;
        $this->dl = $directoryList;
        parent::__construct($context);
    }

    public function getAttributes($params = [])
    {
        $attributes = [];

        try {
            if (isset($params) and is_array($params)) {

                $category = $this->product->create([
                    'config' => $this->config->getApiConfig()
                ]);
                $offerFile = $this->dl->getRoot() . DS . 'app/code/Ced/Betterthat/etc/setupFiles/offer-attributes.json';
                $offerAttributes = file_get_contents($offerFile);
                if ($offerAttributes != null) {
                    $offerAttributes = json_decode($offerAttributes, true);
                }
                $response = $category->getAttributes($params);
                $response = array_merge($response, $offerAttributes);


                $tempmap = ['product-reference-type' => 'title:Product Title',
                            'price' => 'body_html:Description',
                            'state' => 'retailer_id:Retailer Id',
                            'club-Betterthat-eligible' => 'manufacturer:Manufacturer',
                            'product_shipping_options' => 'product_shipping_options:ProductShippingOptions'
                            ];

                $attibute_to_skip = ['category', 'variant-id', 'image-1', 'image-2', 'image-3', 'image-4', 'image-5', 'image-6'];
                if (isset($response) && count($response)) {
                    foreach ($response as $value) {
                        if (isset($value['code']) && in_array(trim($value['code']), $attibute_to_skip))
                            continue;

                        $optionValues = (isset($value['values']['_value']['values_list']['values']['value'])) ? array_column($value['values']['_value']['values_list']['values']['value'], 'label', 'code') : array();
                        $value['option_values'] = $optionValues;
                        if(isset($tempmap[$value['code']])){
                            $data = explode(':',$tempmap[$value['code']]);
                            $value['code'] = @$data[0];
                            $value['label'] = @$data[1];
                        }
                        if ($params['isMandatory']) {
                            if (trim($value['required']) == 'true') {
                                if (isset($this->defaultMapping[$value['code']])) {
                                    $value['magento_attribute_code'] = $this->defaultMapping[$value['code']];
                                }
                                $attributes[$value['code']] = $value;
                            }
                        } else if ($params['isMandatory'] == 0) {
                            if (trim($value['required']) == 'false') {
                                $attributes[$value['code']] = $value;
                            }
                        }
                    }
                }
            }
            return $attributes;
        } catch (\Exception $e) {
            return $attributes;
        }
    }

    public function getCategoriesTree()
    {
        $this->categoriesTree = [];
        $categories = $this->getCategories();
        foreach ($categories as $category) {
            $item = [];
            $item['value'] = $category['code'];
            $item['leaf'] = isset($category['leaf'])?$category['leaf']:0;
            $item['is_active'] = false;
            $item['label'] = $category['label'];
            if (isset($category['children']) and !empty($category['children'])) {
                $item['optgroup'] = $this->generateCategoriesTree($category['children']);
            }
            $this->categoriesTree[] = $item;
        }
        return $this->categoriesTree;
    }

    public function getCategories($params = array())
    {
        if (empty($this->categories)) {
            $product = $this->product->create([
                'config' => $this->config->getApiConfig()
            ]);

            $this->categories = $product->getCategories($params);
        }

        return $this->categories;
    }

    private function generateCategoriesTree(array $categories = [])
    {
        $data = [];
        foreach ($categories as $category) {
            $item = [];
            $item['value'] = $category['code'];
            $item['leaf'] = false;
            $item['is_active'] = false;
            if ($item['leaf']) {
                $item['is_active'] = true;
            }
            $item['label'] = $category['label'];
            if (isset($category['children']) and !empty($category['children'])) {
                $item['optgroup'] = $this->generateCategoriesTree($category['children']);
            }
            $data[] = $item;
        }
        return $data;
    }

    public function getAllAttributes()
    {
        $attributes = [];
        try {
            $category = $this->product->create([
                'config' => $this->config->getApiConfig()
            ]);
            $offerFile = $this->dl->getRoot() . DS . 'app/code/Ced/Betterthat/etc/setupFiles/offer-attributes.json';
            $offerAttributes = file_get_contents($offerFile);
            if ($offerAttributes != null) {
                $offerAttributes = json_decode($offerAttributes, true);
            }
            $response = $category->getAllAttributes();
            $response = array_merge($response, $offerAttributes);
            $attibute_to_skip = ['category', 'variant-id', 'image-1', 'image-2', 'image-3', 'image-4', 'image-5', 'image-6'];
            if (isset($response) && count($response)) {
                foreach ($response as $value) {
                    if (isset($value['code']) && in_array(trim($value['code']), $attibute_to_skip))
                        continue;
                    $attributes[$value['code']] = $value;
                }
            }
            return $attributes;
        } catch (\Exception $e) {
            return $attributes;
        }
    }
}
