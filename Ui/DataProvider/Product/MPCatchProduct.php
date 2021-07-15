<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Ui\DataProvider\Product;

use Ced\Betterthat\Model\ProfileProduct;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Ced\Betterthat\Model\Profile;

/**
 * Class BetterthatProduct
 * @package Ced\Betterthat\Ui\DataProvider\Product
 */
class BetterthatProduct extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var FilterBuilder
     */
    public $filterBuilder;

    /**
     * @var Profile
     */
    public $profile;
    /**
     * @var array
     */
    public $addFieldStrategies;

    /**
     * @var array
     */
    public $addFilterStrategies;

    /** @var \Ced\Betterthat\Helper\Config $config */
    public $config;


    public function __construct(
        CollectionFactory $collectionFactory,
        FilterBuilder $filterBuilder,
        ProfileProduct $profileProduct,
        \Ced\Betterthat\Helper\Config $config,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->filterBuilder    = $filterBuilder;
        $this->collection       = $collectionFactory->create();
        $this->config = $config;

        $this->collection->joinField(
            'qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id = entity_id',
            '{{table}}.stock_id=1',
            null
        );

        $msiSourceCode = $this->config->getMsiSourceCode();
        $useMsi = $this->config->getUseMsi();
        if( $useMsi ) {
            $this->collection->joinField(
                'sourceqty',
                'inventory_source_item',
                'quantity',
                'sku=sku',
                '{{table}}.source_code="' . $msiSourceCode . '"',
                'left'
            );
        }

        $this->addField('Betterthat_product_status');
        $this->addField('Betterthat_profile_id');
        $this->addField('Betterthat_validation_errors');
        $this->addField('Betterthat_feed_errors');
        $this->addFilter(
            $this->filterBuilder->setField('Betterthat_profile_id')->setConditionType('notnull')
                ->setValue('true')
                ->create()
        );
        $this->addFilter(
            $this->filterBuilder->setField('type_id')->setConditionType('in')
                ->setValue(['simple','configurable'])
                ->create()
        );
        $this->addFilter(
            $this->filterBuilder->setField('visibility')->setConditionType('nin')
                ->setValue([1])
                ->create()
        );
        $this->addFieldStrategies   = $addFieldStrategies;
        $this->addFilterStrategies  = $addFilterStrategies;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }

        $items = $this->getCollection()->toArray();
        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items),
        ];
    }


    /**
     * @param \Magento\Framework\Api\Filter $filter
     * @return void
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            if($filter->getField() == 'Betterthat_product_status' && $filter->getValue() == 'NOT_UPLOADED') {
                $filterData = array(
                    array(
                        'attribute' => $filter->getField(),
                        'null' => true),
                    array(
                        'attribute' => $filter->getField(),
                        'eq' => $filter->getValue())
                );
                $this->getCollection()->addAttributeToFilter($filterData);
            } else {
                parent::addFilter($filter);
            }
        }
    }

    /**
     * Add field to select
     *
     * @param  string|array $field
     * @param  string|null  $alias
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }
}
