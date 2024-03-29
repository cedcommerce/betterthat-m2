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
 * @category    Ced
 * @package     Ced_Betterthat
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class MagentoCategoryMapping
 * @package Ced\Betterthat\Model\Source\Profile
 */
class Profiles implements OptionSourceInterface
{

    public $profile;

    public function __construct(
        \Ced\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collectionFactory
    )
    {
        $this->profile = $collectionFactory;
    }

    public function toOptionArray()
    {
        $profiles = $this->profile->create()
            ->addFieldToSelect(['id', 'profile_name']);
        $options = [
            [
                'label' => '  -  ',
                'value' => '',
            ]
        ];
        foreach ($profiles as $profile) {
            $option['label'] = $profile->getProfileName() . " [{$profile->getId()}]";
            $option['value'] = $profile->getId();
            $options[] = $option;
        }
        return $options;
    }


    /**
     * @return array
     */
    public function getOptionArray()
    {
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = (string)$option['label'];
        }

        return $options;
    }

}
