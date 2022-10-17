<?php

/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright Betterthat (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Helper;

class Tax
{
    /**
     * @var \Magento\Tax\Model\Config $taxConfig
     */
    public $taxConfig;

    /**
     * @var \Magento\Tax\Api\TaxCalculationInterface $taxCalculationAPIInterface
     */
    public $taxCalculationAPIInterface;

    /**
     * @var \Magento\Tax\Model\Calculation $taxCalculation
     */
    public $taxCalculation;

    /**
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param \Magento\Tax\Model\Calculation $taxCalculationModel
     * @param \Magento\Tax\Api\TaxCalculationInterface $taxCalculation
     */
    public function __construct(
        \Magento\Tax\Model\Config $taxConfig,
        \Magento\Tax\Model\Calculation $taxCalculationModel,
        \Magento\Tax\Api\TaxCalculationInterface $taxCalculation
    ) {
        $this->taxCalculation = $taxCalculationModel;
        $this->taxConfig = $taxConfig;
        $this->taxCalculationAPIInterface = $taxCalculation;
    }

    /**
     * GetProductTaxRate
     *
     * @param string $productTaxClassId
     * @param string $customerId
     * @param string $storeId
     * @return float
     */
    public function getProductTaxRate($productTaxClassId, $customerId, $storeId)
    {
        return $this->taxCalculationAPIInterface
            ->getCalculatedRate($productTaxClassId, $customerId, $storeId);
    }

    /**
     * GetShippingTaxRate
     *
     * @param mixed $store
     * @return float|int
     */
    public function getShippingTaxRate($store)
    {
        $request = new \Magento\Framework\DataObject();
        $request->setProductClassId($this->taxConfig->getShippingTaxClass($store));
        return $this->taxCalculation->getStoreRate($request, $store);
    }
}
