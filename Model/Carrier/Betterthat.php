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

namespace Betterthat\Betterthat\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;

class Betterthat extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    public const REGISTRY_INDEX_SHIPPING_TOTAL = 'betterthat_shipping_total';

    public const REGISTRY_INDEX_SHIPPING_TAX_PERCENTAGE = 'betterthat_shipping_tax_percentage';

    /**
     * @var string
     */
    public $_code = 'shipbyBetterthat';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    public $_logger;

    /**
     * @var bool
     */
    public $_isFixed = true;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    public $_rateResultFactory;
    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    public $_rateMethodFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * ShipbyBetterthat constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->appState = $appState;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_logger = $logger;
        $this->registry = $registry;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @param RateRequest $request
     * @return bool|\Magento\Shipping\Model\Rate\Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if ($this->appState->getAreaCode() != 'adminhtml' && $this->appState->getAreaCode() != 'crontab') {
            return false;
        }

        $price = $this->registry->registry(self::REGISTRY_INDEX_SHIPPING_TOTAL);
        if ($price > 0) {
            $vatRate = ($this->registry
                ->registry(self::REGISTRY_INDEX_SHIPPING_TAX_PERCENTAGE))
                ? $this->registry->registry(self::REGISTRY_INDEX_SHIPPING_TAX_PERCENTAGE)
                : 0;
            $taxAmount = $vatPrice = ($price * (float)$vatRate) / (100 + $vatRate);
            $price -= $taxAmount;
        }
        if (!isset($price)) {
            $price = 0;
        }

        $handling = $this->getConfigFlag('handling');

        /**
         * @var \Magento\Shipping\Model\Rate\Result $result
         */
        $result = $this->_rateResultFactory->create();

        /**
         * @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method
         */
        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setMethod($this->_code);

        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($price);
        $method->setCost(0);

        $result->append($method);
        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('title')];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }
}
