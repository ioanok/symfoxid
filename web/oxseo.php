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

// mod_rewrite check
if (isset($_REQUEST['mod_rewrite_module_is'])) {
    $sMode = $_REQUEST['mod_rewrite_module_is'];
    if ($sMode == 'on') {
        die("mod_rewrite_on");
    } else {
        die("mod_rewrite_off");
    }
}

/**
 * Detects serchengine URLs
 *
 * @return bool true
 */
function isSearchEngineUrl()
{
    return true;
}

// executing regular routines ...
require 'index.php';
