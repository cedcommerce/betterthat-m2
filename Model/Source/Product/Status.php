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

namespace Betterthat\Betterthat\Model\Source\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

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
     * GetAllOptions
     *
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
