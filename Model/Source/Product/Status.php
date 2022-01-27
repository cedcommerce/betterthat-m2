<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Model\Source\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Status
 *
 * @package Ced\Betterthat\Model\Source
 */
class Status extends AbstractSource
{
    const NOT_UPLOADED = 'NOT_UPLOADED';
    const UPLOADED = 'UPLOADED';
    const LIVE = 'LIVE';
    const INVALID = 'INVALID';
    const INACTIVE = 'INACTIVE';
    const PARTIAL = 'PARTIAL';
    const DELETED = 'DELETED';

    const STATUS = [
        self::NOT_UPLOADED,
        self::INVALID,
        self::UPLOADED,
        self::LIVE,
        self::INACTIVE,
        self::PARTIAL,
        self::DELETED
    ];

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => '',
                'label' => __('--Please Select--'),
            ],
            [
                'value' => self::NOT_UPLOADED,
                'label' => __('Not Uploaded'),
            ],
            [
                'value' => self::UPLOADED,
                'label' => __('Uploaded'),
            ],
            [
                'value' => self::INVALID,
                'label' => __('Invalid'),
            ],
            [
                'value' => self::LIVE,
                'label' => __('Live'),
            ],
            [
                'value' => self::INACTIVE,
                'label' => __('Inactive'),
            ],
            [
                'value' => self::PARTIAL,
                'label' => __('Partial'),
            ],
            [
                'value' => self::DELETED,
                'label' => __('Deleted'),
            ]
        ];
    }
}
