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

namespace Betterthat\Betterthat\Model\Source\Feed;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Status extends AbstractSource
{
    public const SUCCESS = 'success';
    public const FAILURE = 'failure';
    public const SUBMITTED = 'Submitted';
    public const COMPLETE = 'COMPLETE';
    public const SENT = 'SENT';

    /**
     * GetAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => self::FAILURE,
                'label' => self::FAILURE,
            ],
            [
                'value' => self::SUBMITTED,
                'label' => self::SUBMITTED,
            ],
            [
                'value' => self::SUCCESS,
                'label' => self::SUCCESS,
            ],
            [
                'value' => self::SENT,
                'label' => self::SENT,
            ],
            [
                'value' => self::COMPLETE,
                'label' => self::COMPLETE,
            ]
        ];
    }
}
