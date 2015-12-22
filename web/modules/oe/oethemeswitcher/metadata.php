<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.

 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'oethemeswitcher',
    'title'       => 'OXID eShop theme switch',
    'description' => array(
        'de' => 'Modul zum Wechsel der Anzeige zwischen normaler Ansicht und der Ansicht für mobile Endgeräte. Beim Aufruf des OXID eShop durch ein mobiles Endgerät wird ein installiertes Mobile Theme - standardmäßig OXID eShop Mobile Theme - zur Darstellung verwendet. Erfordert ein installiertes Mobile Theme.',
        'en' => 'Module for switching the display between a regular view and a view for mobile devices. If OXID eShop is accessed by a mobile device, an installed mobile theme (OXID eShop mobile theme by default) will be used. An installed mobile theme is required.',
    ),
    'thumbnail'   => 'picture.png',
    'version'     => '1.3.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'http://www.oxid-esales.com',
    'email'       => 'info@oxid-esales.com',
    'extend'      => array(
        'oxconfig'              => 'oe/oethemeswitcher/core/oethemeswitcherconfig',
        'oxtheme'               => 'oe/oethemeswitcher/core/oethemeswitchertheme',
        'oxviewconfig'          => 'oe/oethemeswitcher/core/oethemeswitcherviewconfig',
        'manufacturerlist'      => 'oe/oethemeswitcher/controllers/oethemeswitchermanufacturerlist',
        'alist'                 => 'oe/oethemeswitcher/controllers/oethemeswitcheralist',
        'content'               => 'oe/oethemeswitcher/controllers/oethemeswitchercontent',
        'details'               => 'oe/oethemeswitcher/controllers/oethemeswitcherdetails',
        'review'                => 'oe/oethemeswitcher/controllers/oethemeswitcherreview',
        'rss'                   => 'oe/oethemeswitcher/controllers/oethemeswitcherrss',
        'start'                 => 'oe/oethemeswitcher/controllers/oethemeswitcherstart',
        'tag'                   => 'oe/oethemeswitcher/controllers/oethemeswitchertag',
        'vendorlist'            => 'oe/oethemeswitcher/controllers/oethemeswitchervendorlist',
        'oxlang'                => 'oe/oethemeswitcher/core/oethemeswitcherlang',
        'oxreverseproxybackend' => 'oe/oethemeswitcher/core/cache/oethemeswitcherreverseproxybackend',
    ),
    'files'       => array(
        'oethemeswitcheruseragent'    => 'oe/oethemeswitcher/core/oethemeswitcheruseragent.php',
        'oethemeswitcherthememanager' => 'oe/oethemeswitcher/core/oethemeswitcherthememanager.php',
        'oethemeswitcherevents'       => 'oe/oethemeswitcher/core/oethemeswitcherevents.php',
        'oethemeswitcherwpaymentlist' => 'oe/oethemeswitcher/components/widgets/oethemeswitcherwpaymentlist.php'
    ),

    'blocks'      => array(
        array('template' => 'layout/page.tpl', 'block' => 'layout_page_vatinclude', 'file' => 'views/azure/blocks/theme_switch_link.tpl'),
    ),

    'settings'    => array(
        array('group' => 'main', 'name' => 'sOEThemeSwitcherMobileTheme', 'type' => 'str', 'value' => 'mobile'),
    ),

    'events'      => array(
        'onActivate'   => 'oeThemeSwitcherEvents::onActivate',
        'onDeactivate' => 'oeThemeSwitcherEvents::onDeactivate'
    ),
);
