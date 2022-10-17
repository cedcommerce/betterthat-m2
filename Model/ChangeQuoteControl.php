<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Betterthat\Betterthat\Model;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Quote\Api\ChangeQuoteControlInterface;
use Magento\Quote\Api\Data\CartInterface;

class ChangeQuoteControl extends \Magento\Quote\Model\ChangeQuoteControl
{
    /**
     * @var bool
     */
    protected $forceIsAllowed = false;

    /**
     * ForceIsAllowed
     *
     * @param bool $forceIsAllowed
     * @return $this
     */
    public function forceIsAllowed($forceIsAllowed)
    {
        $this->forceIsAllowed = $forceIsAllowed;
        return $this;
    }

    /**
     * IsAllowed
     *
     * @param CartInterface $quote
     * @return bool
     */
    public function isAllowed(CartInterface $quote): bool
    {
        if ($this->forceIsAllowed) {
            return true;
        } else {
            return parent::isAllowed($quote);
        }
    }
}
