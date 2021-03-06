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


require_once dirname(__FILE__) . "/../bootstrap.php";

// initializes singleton config class
$myConfig = oxRegistry::getConfig();

// executing maintenance tasks..
oxNew("oxmaintenance")->execute();

// closing page, writing cache and so on..
$myConfig->pageClose();
