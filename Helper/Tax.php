<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Helper;

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
     * Tax constructor.
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

    public function getProductTaxRate($productTaxClassId, $customerId, $storeId)
    {
        return $this->taxCalculationAPIInterface
            ->getCalculatedRate($productTaxClassId, $customerId, $storeId);
    }

    public function getShippingTaxRate($store)
    {
        $request = new \Magento\Framework\DataObject();
        $request->setProductClassId($this->taxConfig->getShippingTaxClass($store));
        return $this->taxCalculation->getStoreRate($request, $store);
    }
}
