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

/**
 * Class Type
 *
 * @package Betterthat\Betterthat\Model\Source
 */
class Type extends AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => \BetterthatSdk\Core\Request::FEED_CODE_INVENTORY_UPDATE,
                'label' => \BetterthatSdk\Core\Request::FEED_CODE_INVENTORY_UPDATE,
            ],
            [
                'value' => \BetterthatSdk\Core\Request::FEED_CODE_ITEM_UPDATE,
                'label' => \BetterthatSdk\Core\Request::FEED_CODE_ITEM_UPDATE,
            ],
            [
                'value' => \BetterthatSdk\Core\Request::FEED_CODE_ITEM_DELETE,
                'label' => \BetterthatSdk\Core\Request::FEED_CODE_ITEM_DELETE,
            ],
            [
                'value' => \BetterthatSdk\Core\Request::FEED_CODE_ORDER_SHIPMENT,
                'label' => \BetterthatSdk\Core\Request::FEED_CODE_ORDER_SHIPMENT,
            ],
            [
                'value' => \BetterthatSdk\Core\Request::FEED_CODE_PRICE_UPDATE,
                'label' => \BetterthatSdk\Core\Request::FEED_CODE_PRICE_UPDATE,
            ],
            [
                'value' => \BetterthatSdk\Core\Request::FEED_CANCEL_ORDER_ITEM,
                'label' => \BetterthatSdk\Core\Request::FEED_CANCEL_ORDER_ITEM,
            ],
            [
                'value' => \BetterthatSdk\Core\Request::FEED_CODE_ORDER_CREATE,
                'label' => \BetterthatSdk\Core\Request::FEED_CODE_ORDER_CREATE,
            ]
        ];
    }
}
