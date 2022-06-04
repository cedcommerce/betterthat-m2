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
    public const NOT_UPLOADED = 'NOT_UPLOADED';
    public const UPLOADED = 'UPLOADED';
    public const LIVE = 'LIVE';
    public const INVALID = 'INVALID';
    public const INACTIVE = 'INACTIVE';
    public const PARTIAL = 'PARTIAL';
    public const DELETED = 'DELETED';
    public const STATUS = [
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
                'label' => '--Please Select--',
            ],
            [
                'value' => self::NOT_UPLOADED,
                'label' => 'Not Uploaded',
            ],
            [
                'value' => self::UPLOADED,
                'label' => 'Uploaded',
            ],
            [
                'value' => self::INVALID,
                'label' => 'Invalid',
            ],
            [
                'value' => self::LIVE,
                'label' => 'Live',
            ],
            [
                'value' => self::INACTIVE,
                'label' => 'Inactive',
            ],
            [
                'value' => self::PARTIAL,
                'label' => 'Partial',
            ],
            [
                'value' => self::DELETED,
                'label' => 'Deleted',
            ]
        ];
    }
}
