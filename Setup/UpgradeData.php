<?php

namespace Ced\Betterthat\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Upgrade Data script
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     */
    private $eavAttribute;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavAttribute = $eavAttribute;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $eavSetup = $this->eavSetupFactory->create();
            $groupName = 'Betterthat Marketplace';
            $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
            $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 1000);
            $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);

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

            $setup->endSetup();
        }
        if(version_compare($context->getVersion(), '0.0.3', '<')) {
            $eavSetup = $this->eavSetupFactory->create();
            $groupName = 'Betterthat Marketplace';
            $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
            $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);

            if (!$this->eavAttribute->getIdByCode('catalog_product', 'betterthat_exclude_from_sync')) {
                $eavSetup->addAttribute(
                    'catalog_product',
                    'betterthat_exclude_from_sync',
                    [
                        'group' => $groupName,
                        'note' => 'If yes then product syncing will not done for this product',
                        'input' => 'select',
                        'source' => 'Ced\Betterthat\Model\Source\Yesno',
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
            $setup->endSetup();
        }
    }

}
