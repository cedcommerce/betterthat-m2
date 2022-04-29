<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * @var EavSetupFactory
     */
    public $eavSetupFactory;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     */
    public $eavAttribute;

    /**
     * InstallData constructor.
     *
     * @param EavSetupFactory                                    $eavSetupFactory
     * @param \Magento\Framework\ObjectManagerInterface          $objectManager
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->objectManager = $objectManager;
        $this->eavAttribute = $eavAttribute;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
 		 * @var EavSetup $eavSetup
		 */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $groupName = 'Betterthat Marketplace';
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
        $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 1000);
        $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_state')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_state',
                [
                    'group' => 'Betterthat Marketplace',
                    'note' => 'Please Select State',
                    'input' => 'select',
                    'type' => 'varchar',
                    'label' => 'State',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 1,
                    'user_defined' => 1,
                    'source' => 'Ced\Betterthat\Model\Source\State',
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }


        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_brand')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_brand',
                [
                    'group' => 'Betterthat Marketplace',
                    'note' => '1 to 50 characters',
                    'frontend_class' => 'validate-length maximum-length-50',
                    'input' => 'text',
                    'type' => 'text',
                    'label' => 'Brand',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 3,
                    'user_defined' => 1,
                    'searchable' => 1,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_product_status')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_product_status',
                [
                    'group' => 'Betterthat Marketplace',
                    'note' => 'product status on Betterthat',
                    'input' => 'select',
                    'source' => 'Ced\Betterthat\Model\Source\Product\Status',
                    'type' => 'varchar',
                    'label' => 'Betterthat Product Status',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 12,
                    'user_defined' => 1,
                    'searchable' => 1,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }


        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_validation_errors')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_validation_errors',
                [
                    'group' => 'Betterthat Marketplace',
                    'note' => "Betterthat Validation Errors",
                    'input' => 'text',
                    'type' => 'text',
                    'label' => 'Betterthat Validation Errors',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 14,
                    'user_defined' => 1,
                    'searchable' => 1,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }


        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_feed_errors')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_feed_errors',
                [
                    'group' => 'Betterthat Marketplace',
                    'note' => "Betterthat Feed Errors",
                    'input' => 'text',
                    'type' => 'text',
                    'label' => 'Betterthat Feed Errors',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 14,
                    'user_defined' => 1,
                    'searchable' => 1,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_visibility')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_visibility',
                [
                    'group' => 'Betterthat Marketplace',
                    'note' => "Betterthat Visibility",
                    'input' => 'int',
                    'type' => 'boolean',
                    'label' => 'Betterthat Visibility',
                    'backend' => '',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 14,
                    'user_defined' => 1,
                    'searchable' => 1,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }
    }
}
