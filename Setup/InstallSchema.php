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

namespace Ced\Betterthat\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 *
 * @package Ced\Betterthat\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'Betterthat_profile'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('Betterthat_profile'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )->addColumn(
                'profile_code',
                Table::TYPE_TEXT,
                50,
                [
                    'nullable' => false,
                    'default' => null
                ],
                'Profile Code'
            )
            ->addColumn(
                'profile_status',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 1
                ],
                'Profile Status'
            )
            ->addColumn(
                'profile_name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                ],
                'Profile Name'
            )
            ->addColumn(
                'betterthat_categories',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Betterthat Category'
            )
            ->addColumn(
                'profile_required_attributes',
                Table::TYPE_TEXT,
                '2M',
                [
                    'nullable' => true
                ],
                'Profile Required Attributes'
            )
            ->addColumn(
                'profile_optional_attributes',
                Table::TYPE_TEXT,
                '2M',
                [
                    'nullable' => true,
                ],
                'Profile Optional Attributes'
            )
            ->addColumn(
                'magento_category',
                Table::TYPE_TEXT,
                200,
                [
                    'nullable' => true,
                ],
                'Magento Category'
            )
            ->addIndex(
                $setup->getIdxName(
                    'Betterthat_profile',
                    ['profile_code'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['profile_code'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->setComment('Profile Table')->setOption('type', 'InnoDB')->setOption('charset', 'utf8');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'Betterthat_orders'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('Betterthat_orders'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'Betterthat_order_id',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false, 'default' => ''],
                'Betterthat Order Id'
            )
            ->addColumn(
                'magento_order_id',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false, 'default' => ''],
                'Magento Order Id'
            )
            ->addColumn(
                'increment_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Magento Increament Id'
            )
            ->addColumn(
                'order_place_date',
                Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Order Place Date'
            )
            ->addColumn(
                'status',
                Table::TYPE_TEXT,
                30,
                ['nullable' => true, 'default' => ''],
                'Betterthat Order Status'
            )
            ->addColumn(
                'order_data',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Order Data'
            )
            ->addColumn(
                'order_items',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Order Data'
            )
            ->addColumn(
                'shipments',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => ''],
                'Order Shipment Data'
            )
            ->addColumn(
                'cancellations',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => ''],
                'Order Cancellation Data'
            )
            ->setComment('Betterthat Orders')->setOption('type', 'InnoDB')->setOption('charset', 'utf8');

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'Betterthat_failed_order'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('Betterthat_failed_orders'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'Betterthat_order_id',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false, 'default' => ''],
                'Betterthat Order Id'
            )
            ->addColumn(
                'reason',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => ''],
                'Failed Reason'
            )
            ->addColumn(
                'order_date',
                Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Order Place Date'
            )->addColumn(
                'order_data',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Order Data'
            )->addColumn(
                'order_items',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Order Items'
            )->addColumn(
                'cancellations',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Cancellations'
            )->addColumn(
                'shipments',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Shipments'
            )
            ->addColumn(
                'status',
                Table::TYPE_TEXT,
                20,
                ['nullable' => true, 'default' => ''],
                'Betterthat Order Status'
            )->setComment('Betterthat Failed Orders')->setOption('type', 'InnoDB')->setOption('charset', 'utf8');

        $installer->getConnection()->createTable($table);
    }
}
