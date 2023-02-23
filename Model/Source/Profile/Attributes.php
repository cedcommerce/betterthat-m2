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

namespace Betterthat\Betterthat\Model\Source\Profile;

use Magento\Framework\Data\OptionSourceInterface;

class Attributes implements OptionSourceInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    public $magentoAttributes;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $magentoAttributes
     * @param \Magento\Framework\Json\Helper\Data $json
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $magentoAttributes,
        \Magento\Framework\Json\Helper\Data $json
    ) {
        $this->magentoAttributes = $magentoAttributes;
        $this->json = $json;
    }
    /**
     * ToAllOptions
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getMagentoAttributes();
    }

    /**
     * GetMagentoAttributes
     *
     * @return array
     */
    private function getMagentoAttributes()
    {
        $attributes = $this->magentoAttributes
            ->create()
            ->getItems();
        $magentoAttributes = [];
        $magentoAttributes[''] = [
            'label' => "[ Please select a option ]",
            'value' => "",
            'option_values' => '{}'
        ];
        $magentoAttributes['default_value'] = [
            'label' => "[ Set default value ]",
            'value' => "default_value",
            'option_values' => '{}'
        ];
        foreach ($attributes as $attribute) {
            $type = "";
            $optionValues = "{}";
            $attributeOptions = $attribute->getSource()->getAllOptions(false);
            if (!empty($attributeOptions) && is_array($attributeOptions)) {
                $type = " [ select ]";
                foreach ($attributeOptions as &$option) {
                    if (isset($option['label']) && is_object($option['label'])) {
                        $option['label'] = $option['label']->getText();
                    }
                }
                $attributeOptions = str_replace(
                    '\'',
                    '&#39;',
                    $this->json
                        ->jsonEncode($attributeOptions)
                );
                $optionValues = $attributeOptions;
            }
            $magentoAttributes[$attribute->getAttributecode()]['value'] =
                $attribute->getAttributecode();
            $magentoAttributes[$attribute->getAttributecode()]['label'] =
                is_object($attribute->getFrontendLabel()) ?
                    $attribute->getFrontendLabel()->getText() . $type :
                $attribute->getFrontendLabel() . $type;
            $magentoAttributes[$attribute->getAttributecode()]['option_values'] =
                $optionValues;
        }
        return $magentoAttributes;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = (string)$option['label'];
        }
        return $options;
    }
}
