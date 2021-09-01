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

namespace Ced\Betterthat\Model\Source\Order;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Status
 *
 * @package Ced\Betterthat\Model\Source\Order\Status
 */
class Status extends AbstractSource
{
    const STAGING = 'staging';
    const COMPLETED = 'completed';
    const CANCELED = 'cancelled';
    const CLOSED = 'closed';
    const INPROGRESS = 'inprogress';
    const REFUNDED = 'refunded';
    const SHIPPED = 'shipped';

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => self::INPROGRESS,
                'label' => __('INPROGRESS'),
            ],
            [
                'value' => self::SHIPPED,
                'label' => __('SHIPPED'),
            ],
            [
                'value' => self::CLOSED,
                'label' => __('CLOSED'),
            ],
            [
                'value' => self::CANCELED,
                'label' => __('CANCELED'),
            ],
            [
                'value' => self::REFUNDED,
                'label' => __('REFUNDED'),
            ]
        ];
    }
}
