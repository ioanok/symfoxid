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


if (defined('E_DEPRECATED')) {
    //E_DEPRECATED is disabled particularly for PHP 5.3 as some 3rd party modules still uses deprecated functionality
    error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
} else {
    error_reporting(E_ALL ^ E_NOTICE);
}


if (!defined('OX_BASE_PATH')) {
    define('OX_BASE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

// custom functions file
require_once OX_BASE_PATH . 'modules/functions.php';

// Generic utility method file including autoloading definition
require_once OX_BASE_PATH . 'core/oxfunctions.php';

//sets default PHP ini params
setPhpIniParams();

//init config.inc.php file reader
$oConfigFile = new oxConfigFile(OX_BASE_PATH . "config.inc.php");

oxRegistry::set("oxConfigFile", $oConfigFile);
