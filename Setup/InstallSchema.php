<?php

/**
 * Yudiz
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Yudiz
 * @package     Yudiz_BannerSlider
 * @copyright   Copyright (c) 2023 Yudiz (https://www.Yudiz.com/)
 */

namespace Yudiz\BannerSlider\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('yudiz_slider')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('yudiz_slider'))
                ->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true]
                )
                ->addColumn(
                    'creation_time',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false,
                    'default' => Table::TIMESTAMP_INIT],
                    'Creation Time'
                )
                ->addColumn(
                    'update_time',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false,
                    'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '1'],
                    'Active Status'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Slider Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => false],
                    'Description'
                )
                ->addColumn(
                    'autoplay',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Auto Slider'
                )
                ->addColumn(
                    'autoplay_timeout',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Auto Play Timeout'
                )
                ->addColumn(
                    'reverse_slide',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Reverse Slider'
                )
                ->addColumn(
                    'previous_next',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Previous Next Button'
                )->addColumn(
                    'show_dots',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Show Dots Navigation'
                )
                ->addColumn(
                    'margin',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Margin'
                )
                ->addColumn(
                    'effect',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Animation Effect'
                )
                ->setComment('Slider Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('yudiz_slider_banner_attachment')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('yudiz_slider_banner_attachment'))
                ->addColumn('slider_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true])
                ->addColumn('banner_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true])
                ->addForeignKey(
                    $installer->getFkName(
                        'yudiz_slider',
                        'slider_id',
                        'yudiz_slider_banner_attachment',
                        'slider_id'
                    ),
                    'slider_id',
                    $installer->getTable('yudiz_slider'),
                    'slider_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'yudiz_slider_banner_attachment',
                        'slider_id',
                        'yudiz_banner',
                        'banner_id'
                    ),
                    'banner_id',
                    $installer->getTable('yudiz_banner'),
                    'banner_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Slider Banner Attachment relation table');

            $installer->getConnection()->createTable($table);
        }
        if (!$installer->tableExists('yudiz_banner')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('yudiz_banner'))
                ->addColumn(
                    'banner_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Banner ID'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Banner Title'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Status'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Name'
                )
                ->addColumn(
                    'start_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Start Date'
                )
                ->addColumn(
                    'end_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'End Date'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Description'
                )
                ->addColumn(
                    'mediatype',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Media Type'
                )
                ->addColumn(
                    'uploadfiles',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Uploaded Files'
                )
                ->addColumn(
                    'externalvideo',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'External Video'
                )
                ->addColumn(
                    'creation_time',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false,
                    'default' => Table::TIMESTAMP_INIT],
                    'Creation Time'
                )
                ->addColumn(
                    'update_time',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false,
                    'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                )
                ->setComment('Banners Table');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
