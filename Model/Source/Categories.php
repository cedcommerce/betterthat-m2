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

namespace Betterthat\Betterthat\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

/**
 * Class MagentoCategoryMapping
 *
 * @package Betterthat\Betterthat\Model\Source\Profile
 */
class Categories implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    public $category;

    public $storeManager;

    /**
     * MagentoCategoryMapping constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory     $collectionFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $collectionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->category = $collectionFactory;
    }

    public function toOptionArray()
    {
        $categoryFactory = $this->category;
        $categories = $categoryFactory->create()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('name')
            ->setStore($this->storeManager->getStore());
        $options = [
            [
                'label' => '',
                'value' => '',
            ]
        ];
        foreach ($categories as $category) {
            //if ($category->getLevel() == 2) {
                $option['label'] = $category->getName() . " [{$category->getEntityId()}]";
                $option['value'] = $category->getEntityId();
                $options[] = $option;
            //}
        }
        return $options;
    }
    /**
     * @return array
     */
    public function getOptionArray()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = (string)$option['label'];
        }
        return $options;
    }
}
