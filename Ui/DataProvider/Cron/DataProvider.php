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
namespace Betterthat\Betterthat\Ui\DataProvider\Cron;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Cron\Model\ResourceModel\Schedule\Collection;

/**
 * Class DataProvider
 *
 * @package Betterthat\Betterthat\Ui\DataProvider\Cron
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var $collection
     */
    public $collection;

    /**
     * @var $addFieldStrategies
     */
    public $addFieldStrategies;

    /**
     * @var $addFilterStrategies
     */
    public $addFilterStrategies;

    /**
     * DataProvider constructor.
     *
     * @param string     $name
     * @param string     $primaryFieldName
     * @param string     $requestFieldName
     * @param Collection $collectionFactory
     * @param array      $addFieldStrategies
     * @param array      $addFilterStrategies
     * @param array      $meta
     * @param array      $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Collection $collectionFactory,
        $addFieldStrategies = [],
        $addFilterStrategies = [],
        $meta = [],
        $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->addFieldToFilter(['job_code'], [[ 'like' => "%Betterthat_Betterthat%"]]);
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        $items = $this->getCollection();
        return $items;
    }
}
