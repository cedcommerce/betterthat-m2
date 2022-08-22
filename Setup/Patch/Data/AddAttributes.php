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

namespace Betterthat\Betterthat\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class AddAttributes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     */
    private $eavAttribute;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavAttribute = $eavAttribute;
    }

    public function apply()
    {
        /**
         * @var EavSetup $eavSetup
         */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
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
                    'source' => \Betterthat\Betterthat\Model\Source\State::class,
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
                    'source' => \Betterthat\Betterthat\Model\Source\Product\Status::class,
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
                    'input' => 'select',
                    'type' => 'int',
                    'label' => 'Betterthat Visibility',
                    'backend' => '',
                    'frontend_input' => 'boolean',
                    'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
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
        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_profile_id')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_profile_id',
                [
                    'group' => $groupName,
                    'note' => 'Betterthat Profile Id',
                    'input' => 'text',
                    'type' => 'varchar',
                    'label' => 'Betterthat Profile Id ',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 1,
                    'user_defined' => 1,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }
        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_product_id')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_product_id',
                [
                    'group' => $groupName,
                    'note' => 'Betterthat Product Id',
                    'input' => 'text',
                    'type' => 'varchar',
                    'label' => 'Betterthat Product Id ',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 1,
                    'user_defined' => 1,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }
        if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_feed_errors')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'betterthat_feed_errors',
                [
                    'group' => $groupName,
                    'note' => 'Betterthat Feeds',
                    'input' => 'text',
                    'type' => 'varchar',
                    'label' => 'Betterthat Feeds',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 1,
                    'user_defined' => 1,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }
        $eavSetup = $this->eavSetupFactory->create();
        $groupName = 'Betterthat Marketplace';
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
        $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);

        $eavSetup->addAttribute(
            'catalog_product',
            'betterthat_exclude_from_sync',
            [
                'group' => $groupName,
                'note' => 'If yes then product syncing will not done for this product',
                'input' => 'select',
                'source' => \Betterthat\Betterthat\Model\Source\Yesno::class,
                'type' => 'varchar',
                'label' => 'Exclude From Sync',
                'backend' => '',
                'visible' => 1,
                'required' => 0,
                'sort_order' => 12,
                'user_defined' => 1,
                'searchable' => 1,
                'visible_on_front' => 0,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            ]
        );
    }
    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '0.0.1';
    }
}
