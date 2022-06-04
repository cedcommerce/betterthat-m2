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

namespace Ced\Betterthat\Model\Source\Order;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Status
 *
 * @package Ced\Betterthat\Model\Source\Order\Status
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
