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
        'product_shipping_options' => '',
        'shipping_option_charges' => '',
        'product_return_window' => '',

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
            if (isset($params) and @$params['isMandatory'] == 1) {
                $attributes = [
        "title" => [
                    "code" => "title",
                    "default_value" => "",
                    "description" => "",
                    "description_translations" => [
                ],
                "example" => "",
                "hierarchy_code" => "",
                "label" => "Title",
                "label_translations" => [
                     [

                        "locale" => "en",
                        "value" => "Title"
                     ],

                ],
            "required" => true,
                "roles" => [
                    [
                        "parameters" => [
                        ],
                        "type" => "Title"
                        ]

                ],

            "type" => "TEXT",
            "type_parameter" => "",
            "values" => "",
            "values_list" => "",
            "variant" => "",
            "options" => "",
            "option_values" => [
                "ean" => "EAN",
                "upc" => "UPC",
                "isbn" => "ISBN",
                "mpn" => "MPN"
            ],
            "magento_attribute_code" => "name",
        ],

    "body_html" => [

            "code" => "body_html",
            "default_value" => "",
            "description" => "",
            "description_translations" => [

                ],
            "example" => "",
            "hierarchy_code" => "",
            "label" => "Description",
            "label_translations" => [
                     [
                        "locale" => "en",
                        "value" => "Description"
                      ]

                ],
            "required" => true,
                "roles" => [
                    [
                        "parameters" => [
                        ],
                        "type" => "Description",
                    ]
                ],
            "type" => "TEXT",
            "type_parameter" => "",
            "values" => "",
            "magento_attribute_code" => "description",
            "option_values" => [

            ]

                ],
                "manufacturer" => [

                    "code" => "manufacturer",
                    "default_value" => "",
                    "description" => "",
                    "description_translations" => [

                    ],
                    "example" => "",
                    "hierarchy_code" => "",
                    "label" => "Manufacturer",
                    "label_translations" => [
                        [
                            "locale" => "en",
                            "value" => "Manufacturer"
                        ]

                    ],
                    "required" => true,
                    "roles" => [
                        [
                            "parameters" => [
                            ],
                            "type" => "Manufacturer",
                        ]
                    ],
                    "type" => "TEXT",
                    "type_parameter" => "",
                    "values" => "",
                    "magento_attribute_code" => "manufacturer",
                    "option_values" => [

                    ]

                ],
                "product_shipping_options" => [

                    "code" => "product_shipping_options",
                    "default_value" => "",
                    "description" => "",
                    "description_translations" => [

                    ],
                    "example" => "",
                    "hierarchy_code" => "",
                    "label" => "Shipping Options",
                    "label_translations" => [
                        [
                            "locale" => "en",
                            "value" => "Product Shipping Options"
                        ]

                    ],
                    "required" => true,
                    "roles" => [
                        [
                            "parameters" => [
                            ],
                            "type" => "Product Shipping Options",
                        ]
                    ],
                    "type" => "LIST",
                    "type_parameter" => "",
                    "values" => "",
                    "magento_attribute_code" => "",
                    "option_values" => [
                        "Instore" => "Instore",
                        "Standard" => "Standard",
                        "Expedited" => "Expedited"

                    ]

                ],
                    "shipping_option_charges" => [
                        "code" => "shipping_option_charges",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [

                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Shipping Options Charges",
                        "label_translations" => [
                            [
                                "locale" => "en",
                                "value" => "Shipping Options Charges"
                            ]

                        ],
                        "required" => true,
                        "type" => "LIST",
                        "type_parameter" => "",
                        "values" => "",
                        "magento_attribute_code" => "",
                        "option_values" => [

                        ]

                    ],
                    "product_return_window" => [
                        "code" => "product_return_window",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [

                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Product Return Window",
                        "label_translations" => [
                            [
                                "locale" => "en",
                                "value" => "Product Return Window"
                            ]

                        ],
                        "required" => true,
                        "roles" => [
                            [
                                "parameters" => [
                                ],
                                "type" => "Product Return Window",
                            ]
                        ],
                        "type" => "LIST",
                        "type_parameter" => "",
                        "values" => "",
                        "magento_attribute_code" => "",
                        "option_values" => [
                        ]
                    ],
                    "can_be_bundled" => [
                        "code" => "can_be_bundled",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [
                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Can be bundled",
                        "label_translations" => [
                            [
                                "locale" => "en",
                                "value" => "Can be bundled"
                            ]

                        ],
                        "required" => true,
                        "type" => "LIST",
                        "type_parameter" => "",
                        "values" => "",
                        "magento_attribute_code" => "",
                        "option_values" => [
                            "Yes" => "Yes",
                            "No" => "No"
                        ]

                    ]
                ];
            }else{
                // optional attribute
                $attributes = [
                    "slug" => [
                        "code" => "slug",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [
                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Slug/URL Key",
                        "label_translations" => [
                            [

                                "locale" => "en",
                                "value" => "Slug"
                            ],

                        ],
                        "required" => false,
                        "roles" => [
                            [
                                "parameters" => [
                                ],
                                "type" => "Slug"
                            ]

                        ],

                        "type" => "TEXT",
                        "type_parameter" => "",
                        "values" => "",
                        "values_list" => "",
                        "variant" => "",
                        "options" => "",
                        "option_values" => [
                        ],
                        "magento_attribute_code" => "url_key",
                    ],

                    "policy_description_option" => [

                        "code" => "policy_description_option",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [

                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Policy Description Option",
                        "label_translations" => [
                            [
                                "locale" => "en",
                                "value" => "Policy Desc Option"
                            ]

                        ],
                        "required" => false,
                        "roles" => [
                            [
                                "parameters" => [
                                ],
                                "type" => "Description",
                            ]
                        ],
                        "type" => "TEXT",
                        "type_parameter" => "",
                        "values" => "",
                        "magento_attribute_code" => "",
                        "option_values" => [

                        ]

                    ],
                    "policy_description_val" => [
                        "code" => "policy_description_val",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [

                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Policy Description Value",
                        "label_translations" => [
                            [
                                "locale" => "en",
                                "value" => "Policy Description Value"
                            ]

                        ],
                        "required" => true,
                        "type" => "TEXT",
                        "type_parameter" => "",
                        "values" => "",
                        "magento_attribute_code" => "",
                        "option_values" => [

                        ]

                    ],
                    "standard_delivery_timeframe" => [

                        "code" => "standard_delivery_timeframe",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [

                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Standard Delivery Timeframe",
                        "label_translations" => [
                            [
                                "locale" => "en",
                                "value" => "Standard Delivery timeframe"
                            ]

                        ],
                        "required" => true,
                        "type" => "LIST",
                        "type_parameter" => "",
                        "values" => "",
                        "magento_attribute_code" => "",
                        "option_values" => [

                        ]

                    ],
                    "international_shipping" => [
                        "code" => "international_shipping",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [
                        ],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "International Shipping Timeframe",
                        "label_translations" => [
                            [
                                "locale" => "en",
                                "value" => "International Shipping"
                            ]
                        ],
                        "required" => true,
                        "type" => "LIST",
                        "type_parameter" => "",
                        "values" => "",
                        "magento_attribute_code" => "",
                        "option_values" => [
                            "Yes" => "Yes",
                            "No" => "No"
                        ]

                    ]
                ];
            }
            return $attributes;
        } catch (\Exception $e) {
            return $attributes;
        }
    }

    public function getCategoriesTree()
    {
        $this->categoriesTree = [];
        $categoryJson = file_get_contents(__DIR__.'/allcategories.json');
       $categoryArray = json_decode($categoryJson,1);
        $this->categoriesTree = $this->getCategoriesTreeNode($categoryArray);
        return $this->categoriesTree;
    }

    /*
* @ Declare the function to find hierarchy
*/
    public function getHierarchy($array){
        // echo "<pre>";
        // print_r($array);


        // id for parent id null: 5d5fbcd2e6802f178f97403c
        for($i = 0; $i< count($array); $i++){
            $array[$i]['hierarchy'] = '';
            // echo $array[$i]['parent_id']."<br>";

            if($array[$i]['parent_id'] != ''){
                $id_level1 = $array[$i]['parent_id'];
                if($id_level1 != ''){
                    for($j = 0; $j< count($array); $j++){
                        if($array[$j]['_id'] == $id_level1){
                            $array[$i]['hierarchy'] = $array[$j]['Name']." > ".$array[$i]['Name'];
                            $id_level2 = $array[$j]['parent_id'];

                            if($id_level2 != ''){
                                for($k = 0; $k< count($array); $k++){
                                    if($array[$k]['_id'] == $id_level2){
                                        $array[$i]['hierarchy'] = $array[$k]['Name']." > ".$array[$j]['Name']." > ".$array[$i]['Name'];

                                        $id_level3 = $array[$k]['parent_id'];
                                        if($id_level3 != ''){
                                            for($l = 0; $l< count($array); $l++){
                                                if($array[$l]['_id'] == $id_level3){
                                                    if($array[$l]['parent_id'] == '' && $array[$k]['parent_id'] != ''){
                                                        $array[$i]['hierarchy'] = $array[$l]['Name']." > ".$array[$k]['Name']." > ".$array[$j]['Name']." > ".$array[$i]['Name'];
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }
            else{
                $array[$i]['hierarchy'] = $array[$i]['Name'];
            }
        }

        return $array;
    }

    public function getCategoriesTreeNode($jsonString)
    {

        // $this->categoriesTree = [];
        $categoriesTree = [];
        $categories = $this->getHierarchy($jsonString);
        //$categories = json_decode($categories_json);
        $isActive = true;

        // echo "<pre>";
        // print_r($categories); exit;

        $item = [];
        for($i=0; $i<count($categories); $i++){
            if($categories[$i]['parent_id'] == ''){
                $val = [];
                $val['value'] = $categories[$i]['_id'];
                $val['is_active'] = $isActive;
                $val['label'] = $categories[$i]['Name'];
                $val['optgroup'] = [];
                for($j=0; $j<count($categories); $j++){
                    if($categories[$i]['_id'] == $categories[$j]['parent_id']){
                        $new_val = [];
                        $new_val['value'] = $categories[$j]['_id'];
                        $new_val['is_active'] = $isActive;
                        $new_val['label'] = $categories[$j]['Name'];
                        $new_val['optgroup'] = [];

                        for($k=0; $k<count($categories); $k++){
                            if($categories[$j]['_id'] == $categories[$k]['parent_id']){
                                $new_val1 = [];
                                $new_val1['value'] = $categories[$k]['_id'];
                                $new_val1['is_active'] = $isActive;
                                $new_val1['label'] = $categories[$k]['Name'];
                                $new_val1['optgroup'] = [];

                                for($l=0; $l<count($categories); $l++){
                                    if($categories[$k]['_id'] == $categories[$l]['parent_id']){
                                        $new_val2 = [];
                                        $new_val2['value'] = $categories[$l]['_id'];
                                        $new_val2['is_active'] = $isActive;
                                        $new_val2['label'] = $categories[$l]['Name'];
                                        array_push($new_val1['optgroup'],$new_val2);
                                    }
                                }
                                array_push($new_val['optgroup'],$new_val1);
                            }
                        }
                        array_push($val['optgroup'],$new_val);
                    }
                }

                array_push($item, $val);
            }
        }
        return $item;
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

   /* private function generateCategoriesTree(array $categories = [])
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
    }*/

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
