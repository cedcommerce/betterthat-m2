<?php

/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Ui\DataProvider\Profile;

use Betterthat\Betterthat\Model\ProfileProduct;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Betterthat\Betterthat\Model\Profile;

class Products extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    public $addFieldStrategies;

    /**
     * @var array
     */
    public $addFilterStrategies;

    /**
     * @var FilterBuilder
     */
    public $filterBuilder;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var \Magento\Ui\Model\Bookmark
     */
    public $bookmark;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Magento\Backend\App\Action\Context $context
     * @param CollectionFactory $collectionFactory
     * @param FilterBuilder $filterBuilder
     * @param \Magento\Ui\Model\BookmarkFactory $bookmark
     * @param array $addFieldStrategies
     * @param array $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Backend\App\Action\Context $context,
        CollectionFactory $collectionFactory,
        FilterBuilder $filterBuilder,
        \Magento\Ui\Model\BookmarkFactory $bookmark,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->bookmark = $bookmark;
        $this->filterBuilder = $filterBuilder;
        $this->collection = $collectionFactory->create();
        $this->request = $context->getRequest();

        $profileId = $this->request->getParam('Betterthat_profile_id');
        $bookmarks = $this->bookmark->create()
            ->getCollection()
            ->addFieldToFilter('namespace', ['eq' => 'sears_products_index']);
        if (isset($profileId) && !empty($profileId)) {
            foreach ($bookmarks as $bookmark) {
                if ($bookmark->getIdentifier() == 'current') {
                    $configValue = $bookmark->getConfig();
                    $configValue['current']['filters']['applied']['Betterthat_profile_id'] = $profileId;
                    $bookmark->setConfig(json_encode($configValue));
                    $bookmark->save();
                }
            }
        } else {
            foreach ($bookmarks as $bookmark) {
                if ($bookmark->getIdentifier() == 'current') {
                    $configValue = $bookmark->getConfig();
                    if (isset($configValue['current']['filters']['applied']['Betterthat_profile_id'])) {
                        unset($configValue['current']['filters']['applied']['Betterthat_profile_id']);
                    }
                    $bookmark->setConfig(json_encode($configValue));
                    $bookmark->save();
                }
            }
        }
        $this->addField('Betterthat_product_status');
        $this->addField('Betterthat_validation_errors');
        $this->addField('Betterthat_feed_errors');
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
    }

    /**
     * AddFilter
     *
     * @param  \Magento\Framework\Api\Filter $filter
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
            parent::addFilter($filter);
        }
    }

    /**
     * AddField
     *
     * @param string $field
     * @param string $alias
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]
                ->addField(
                    $this->getCollection(),
                    $field,
                    $alias
                );
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * GetData
     *
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
}
