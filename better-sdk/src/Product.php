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
 * @package     Betterthat-Sdk
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

namespace BetterthatSdk;

class Product extends \BetterthatSdk\Core\Request
{
    /**
     * @param $params
     * @return array
     */
    public function getCategories($params)
    {
        $params['level'] = empty($params['level']) ? 0 : $params['level'];
        $categories = [];
        $levelCollect = [];
        try {
                if (!file_exists(__DIR__ . '/allcategories.json')){
                    $response = $this->postRequest(self::GET_CATEGORIES_SUB_URL, $params);
                     file_put_contents(__DIR__ . '/allcategories.json', $response);
                }else{
                    $response = file_get_contents(__DIR__ . '/allcategories.json');
                }
                $categories = json_decode($response,1);
                switch($params['level']){
                    case 0:
                        $levelCollect = [];
                        foreach ($categories as $category)
                        {
                            if($category['parent_id'] == null)
                            {
                                $category['level'] = 0;
                                $levelCollect[] = $category;
                            }

                        }
                    break;
                    case 1:
                        $levelCollect = [];
                        foreach ($categories as $category)
                        {
                            if($category['parent_id'] == $params['category_id'])
                            {
                                $category['level'] = 1;
                                $levelCollect[] = $category;
                            }

                        }
                        break;
                    case 2:
                        $levelCollect = [];
                        foreach ($categories as $category)
                        {
                            if($category['parent_id'] == $params['category_id'])
                            {
                                $category['level'] = 2;
                                $levelCollect[] = $category;
                            }

                        }
                        break;
                    case 3:
                        $levelCollect = [];
                        foreach ($categories as $category)
                        {
                            if($category['parent_id'] == $params['category_id'])
                            {
                                $category['level'] = 3;
                                $levelCollect[] = $category;
                            }

                        }
                        break;
                    case 4:
                        $levelCollect = [];
                        foreach ($categories as $category)
                        {
                            if($category['parent_id'] == $params['category_id'])
                            {
                                $category['level'] = 4;
                                $levelCollect[] = $category;
                            }

                        }
                        break;

                }

                return $levelCollect;

        } catch (\Exception $e) {
            if ($this->debugMode) {
                $this->logger->debug(
                    "BetterthatSdk\\Product\\getCategories() : Errors: " . var_export($e->getMessage(), true)
                );
            }
        }

        return $categories;
    }

    /**
     * @param $product_data
     * @return false|string
     */
    public function createProduct($product_data)
    {
        if (is_array($product_data) && count($product_data) > 0 ) {
            $response = $this->postRequest(self::POST_ITEMS_SUB_URL, array('data' => $product_data));
            return $response;
        }
        return false;
    }

    /**
     * @param $data
     * @return false|string
     */
    public function updateInventory($data)
    {
        if ($data) {
           return $response = $this->postRequest(self::POST_INVPRICE, ['data' => $data]);
        }
        return false;
    }


}
