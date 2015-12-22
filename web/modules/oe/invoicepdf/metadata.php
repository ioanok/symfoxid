<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 * @version   OXID eShop PE
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.0';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'invoicepdf',
    'title'       => 'Invoice PDF',
    'description' => 'Module to export invoice PDF files.',
    'thumbnail'   => 'picture.png',
    'version'     => '1.0',
    'author'      => 'OXID eSales AG',
    'extend'      => array(
        'oxorder'        => 'oe/invoicepdf/models/invoicepdfoxorder',
        'order_overview' => 'oe/invoicepdf/controllers/admin/invoicepdforder_overview'
    ),
    'files'       => array(
        'InvoicepdfBlock'          => 'oe/invoicepdf/models/invoicepdfblock.php',
        'InvoicepdfArticleSummary' => 'oe/invoicepdf/models/invoicepdfarticlesummary.php'
    ),
    'blocks'      => array(
        array(
            'template' => 'order_overview.tpl',
            'block'    => 'admin_order_overview_export',
            'file'     => 'views/admin/blocks/order_overview.tpl'
        ),
    ),
);
