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

namespace Betterthat\Betterthat\Model\Source\Order;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Status
 *
 * @package Betterthat\Betterthat\Model\Source\Order\Status
 */
class Status extends AbstractSource
{
    public const PENDING = 'pending';
    public const INPROGRESS = 'inprogress';
    public const COMPLETE = 'complete';
    public const SHIPPED = 'Shipped';
    public const DELIVERED = 'Delivered';
    public const CANCELLED = 'cancelled';

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => self::PENDING,
                'label' => __('PENDING'),
            ],
            [
                'value' => self::INPROGRESS,
                'label' => __('INPROGRESS'),
            ],
            [
                'value' => self::COMPLETE,
                'label' => __('COMPLETE'),
            ],
            [
                'value' => self::SHIPPED,
                'label' => __('SHIPPED'),
            ],
            [
                'value' => self::DELIVERED,
                'label' => __('DELIVERED'),
            ],
            [
                'value' => self::CANCELLED,
                'label' => __('CANCELLED'),
            ]
        ];
    }
}
