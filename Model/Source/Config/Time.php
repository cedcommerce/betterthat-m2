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
 * @package   Ced_Wish
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Model\Source\Config;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class ServiceUrl
 *
 * @package Betterthat\Wish\Model\Source
 */
class Time extends AbstractSource
{
    public const CRON_5MINUTES = '*/5 * * * *';
    public const CRON_15MINUTES = '*/15 * * * *';
    public const CRON_30MINUTES = '*/30 * * * *';
    public const CRON_HOURLY = '0 * * * *';
    public const CRON_2HOURLY = '0 */2 * * *';
    public const CRON_6HOURLY = '0 */2 * * *';
    public const CRON_12HOURLY = '0 0,12 * * *';
    public const CRON_DAILY = '0 0 * * *';

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $expressions = [
            [
            'label' => __('Every  5 Minutes'),
            'value' => self::CRON_5MINUTES
            ],
            [
                'label' => __('Every  15 Minutes'),
                'value' => self::CRON_15MINUTES
            ],
            [
                'label' => __('Every 30 Minutes'),
                'value' => self::CRON_30MINUTES
            ],
            [
                'label' => __('Every Hour'),
                'value' => self::CRON_HOURLY
            ],
            [
                'label' => __('Every 2 Hours'),
                'value' => self::CRON_2HOURLY
            ],
            [
                'label' => __('Every 6 Hours'),
                'value' => self::CRON_6HOURLY
            ],
            [
                'label' => __('Twice A Day'),
                'value' => self::CRON_12HOURLY
            ],
            [
                'label' => __('Once A Day'),
                'value' => self::CRON_DAILY
            ],
        ];

        return $expressions;
    }
}
