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
 * @package   Ced_Ebay
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Model\Payment;

class Betterthat extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * @var string
     */
    public $_code = 'paybyBetterthat';

    /**
     * @var bool
     */
    public $_canAuthorize = true;

    /**
     * @var bool
     */
    public $_canCancelInvoice = false;

    /**
     * @var bool
     */
    public $_canCapture = false;

    /**
     * @var bool
     */
    public $_canCapturePartial = false;

    /**
     * @var bool
     */
    public $_canCreateBillingAgreement = false;

    /**
     * @var bool
     */
    public $_canFetchTransactionInfo = false;

    /**
     * @var bool
     */
    public $_canManageRecurringProfiles = false;

    /**
     * @var bool
     */
    public $_canOrder = false;

    /**
     * @var bool
     */
    public $_canRefund = false;

    /**
     * @var bool
     */
    public $_canRefundInvoicePartial = false;

    /**
     * @var bool
     */
    public $_canReviewPayment = false;

    /**
     * @var bool
     */
    public $_canUseCheckout = false;

    /**
     * @var bool
     */
    public $_canUseForMultishipping = false;

    /**
     * @var bool
     */
    public $_canUseInternal = false;

    /**
     * @var bool
     */
    public $_canVoid = false;

    /**
     * @var bool
     */
    public $_isGateway = false;

    /**
     * @var bool
     */
    public $_isInitializeNeeded = false;

    /* END */

    /**
     * IsAvailable
     *
     * @param  \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return true;
    }

    /**
     * GetCode
     *
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }
}
