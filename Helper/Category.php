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
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Exception;
use Magento\Framework\Filesystem\DriverPool;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var ObjectManagerInterface
     */
    public $objectManager;
    /**
     * @var Config
     */
    public $config;
    /**
     * @var \BetterthatSdk\ProductFactory
     */
    public $product;
    /**
     * @var array
     */
    public $categories = [];
    /**
     * @var array
     */
    public $categoriesTree = [];
    /**
     * @var string[]
     */
    public $defaultMapping = [
        'title' => 'name',
        'body_html' => 'description',
        'retailer_id' => 'retailer_id',
        'manufacturer' => 'manufacturer',
        'product_shipping_options' => '',
        'shipping_option_charges' => '',
        'product_return_window' => '',
    ];

    /**
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param Config $config
     * @param \BetterthatSdk\ProductFactory $product
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        \Betterthat\Betterthat\Helper\Config $config,
        \BetterthatSdk\ProductFactory $product,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
    ) {
        $this->objectManager = $objectManager;
        $this->product = $product;
        $this->config = $config;
        $this->dl = $directoryList;
        $this->filedriver = $readFactory;
        parent::__construct($context);
    }

    /**
     * GetAttributes
     *
     * @param array $params
     * @return array|array[]
     */
    public function getAttributes($params = [])
    {
        $attributes = [];
        try {
            if (isset($params['isMandatory']) && $params['isMandatory'] == 1) {
                $attributes = [
                    "title" => [
                        "code" => "title",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [],
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
                        ],
                        "magento_attribute_code" => "name",
                    ],
                    "short_description" => [
                        "code" => "short_description",
                        "default_value" => "",
                        "description" => "",
                        "description_translations" => [],
                        "example" => "",
                        "hierarchy_code" => "",
                        "label" => "Short Description",
                        "label_translations" => [
                            [

                                "locale" => "en",
                                "value" => "Short Description"
                            ],

                        ],
                        "required" => true,
                        "roles" => [
                            [
                                "parameters" => [
                                ],
                                "type" => "Short Description"
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
                        "magento_attribute_code" => "short_description",
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
            } else {
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

    /**
     * GetCategoriesTree
     *
     * @return array
     */
    public function getCategoriesTree()
    {
        $this->categoriesTree = [];
        $categoryJson = $this->filedriver->create(__DIR__, DriverPool::FILE)
            ->readFile('allcategories.json');
        $categoryArray = json_decode($categoryJson, 1);
        $this->categoriesTree = $this->getCategoriesTreeNode($categoryArray);
        return $this->categoriesTree;
    }

    /**
     * GetHierarchy
     *
     * @param  array $array
     * @return mixed
     */
    public function getHierarchy($array)
    {
        $length = count($array);
        for ($i = 0; $i < $length; $i++) {
            $array[$i]['hierarchy'] = '';

            if ($array[$i]['parent_id'] != '') {
                $id_level1 = $array[$i]['parent_id'];
                if ($id_level1 != '') {
                    $array = $this->innerHeirarchy($array, $id_level1, $i);
                }
            } else {
                $array[$i]['hierarchy'] = $array[$i]['Name'];
            }
        }
        return $array;
    }

    /**
     * InnerHeirarchy
     *
     * @param array $array
     * @param string $id_level1
     * @param string $i
     * @return mixed
     */
    public function innerHeirarchy($array, $id_level1, $i)
    {
        $length = count($array);
        for ($j = 0; $j < $length; $j++) {
            if ($array[$j]['_id'] == $id_level1) {
                $array[$i]['hierarchy'] = $array[$j]['Name'] . " > " . $array[$i]['Name'];
                $id_level2 = $array[$j]['parent_id'];
                if ($id_level2 != '') {
                    $length = count($array);
                    // refactor //
                    $this->innerHeirarchyone($array, $id_level2, $j, $i);
                }
            }
        }
        return $array;
    }

    /**
     * InnerHeirarchyone
     *
     * @param array $array
     * @param string $id_level2
     * @param string $j
     * @param string $i
     * @return mixed
     */
    public function innerHeirarchyone($array, $id_level2, $j, $i)
    {
        $length = count($array);
        for ($k = 0; $k < $length; $k++) {
            if ($array[$k]['_id'] == $id_level2) {
                $array[$i]['hierarchy'] = $array[$k]['Name']
                    . " > "
                    . $array[$j]['Name']
                    . " > "
                    . $array[$i]['Name'];

                $array = $this->innerHeirarchytwo($array, $k, $j, $i);
            }
        }
        return $array;
    }

    /**
     * InnerHeirarchytwo
     *
     * @param array $array
     * @param string $k
     * @param string $j
     * @param string $i
     * @return mixed
     */
    public function innerHeirarchytwo($array, $k, $j, $i)
    {
        $id_level3 = $array[$k]['parent_id'];
        if ($id_level3 != '') {
            $length = count($array);
            for ($l = 0; $l < $length; $l++) {
                if ($array[$l]['_id'] == $id_level3) {
                    if ($array[$l]['parent_id'] == ''
                        && $array[$k]['parent_id'] != '') {
                        $array[$i]['hierarchy'] =
                            $array[$l]['Name'] . " > "
                            . $array[$k]['Name'] . " > "
                            . $array[$j]['Name'] . " > "
                            . $array[$i]['Name'];
                    }
                }
            }
        }
        return $array;
    }
    /**
     * GetCategoriesTreeNode
     *
     * @param  json $jsonString
     * @return array
     */
    public function getCategoriesTreeNode($jsonString)
    {
        $categoriesTree = [];
        $categories = $this->getHierarchy($jsonString);
        $isActive = true;
        $item = [];
        $length = count($categories);
        for ($i = 0; $i < $length; $i++) {
            if ($categories[$i]['parent_id'] == '') {
                $val = [];
                $val['value'] = $categories[$i]['_id'];
                $val['is_active'] = $isActive;
                $val['label'] = $categories[$i]['Name'];
                $val['optgroup'] = [];
                $length = count($categories);
                for ($j = 0; $j < $length; $j++) {
                    if ($categories[$i]['_id'] == $categories[$j]['parent_id']) {
                        $new_val = [];
                        $new_val['value'] = $categories[$j]['_id'];
                        $new_val['is_active'] = $isActive;
                        $new_val['label'] = $categories[$j]['Name'];
                        $new_val['optgroup'] = [];
                        $length = count($categories);
                        $new_val = $this->innergetCategoryTreeNodeone($new_val, $length, $categories, $j, $isActive);
                        array_push($val['optgroup'], $new_val);
                    }
                }
                array_push($item, $val);
            }
        }
        return $item;
    }

    /**
     * InnergetCategoryTreeNodeone
     *
     * @param string $new_val
     * @param string $length
     * @param array $categories
     * @param string $j
     * @param bool $isActive
     * @return mixed
     */
    public function innergetCategoryTreeNodeone($new_val, $length, $categories, $j, $isActive)
    {
        for ($k = 0; $k < $length; $k++) {
            if ($categories[$j]['_id'] == $categories[$k]['parent_id']) {
                $new_val1 = [];
                $new_val1['value'] = $categories[$k]['_id'];
                $new_val1['is_active'] = $isActive;
                $new_val1['label'] = $categories[$k]['Name'];
                $new_val1['optgroup'] = [];
                $length = count($categories);
                //refactor
                $new_val1 =
                    $this->innergetCatrgoryTreeNodes($new_val1, $length, $categories, $k, $isActive);
                array_push($new_val['optgroup'], $new_val1);
            }
        }
        return $new_val;
    }

    /**
     * InnergetCatrgoryTreeNodes
     *
     * @param string $new_val1
     * @param string $length
     * @param array $categories
     * @param string $k
     * @param bool $isActive
     * @return mixed
     */
    public function innergetCatrgoryTreeNodes($new_val1, $length, $categories, $k, $isActive)
    {
        for ($l = 0; $l < $length; $l++) {
            if ($categories[$k]['_id'] == $categories[$l]['parent_id']) {
                $new_val2 = [];
                $new_val2['value'] = $categories[$l]['_id'];
                $new_val2['is_active'] = $isActive;
                $new_val2['label'] = $categories[$l]['Name'];
                array_push($new_val1['optgroup'], $new_val2);
            }
        }
        return $new_val1;
    }

    /**
     * GetCategories
     *
     * @param array $params
     * @return array
     */
    public function getCategories($params = [])
    {
        if (empty($this->categories)) {
            $product = $this->product->create(
                [
                    'config' => $this->config->getApiConfig()
                ]
            );
            $this->categories = $product->getCategories($params);
        }
        return $this->categories;
    }
}
