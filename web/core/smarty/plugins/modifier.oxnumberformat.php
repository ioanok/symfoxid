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
 * Smarty modifier
 * -------------------------------------------------------------
 * Name:     smarty_modifier_oxnumberformat<br>
 * Purpose:  Formats number for chosen locale
 * Example:  $object = "EUR@ 1.00@ ,@ .@ EUR@ 2"{$object|oxnumberformat:2000.123}
 * -------------------------------------------------------------
 *
 * @param string $sFormat Number formatting rules (use default currency formatting rules defined in Admin)
 * @param string $sValue  Number to format
 *
 * @return string
 */
function smarty_modifier_oxnumberformat( $sFormat = "EUR@ 1.00@ ,@ .@ EUR@ 2", $sValue = 0)
{
    // logic copied from oxconfig::getCurrencyArray()
    $sCur = explode( "@", $sFormat);
    $oCur           = new stdClass();
    $oCur->id       = 0;
    $oCur->name     = @trim($sCur[0]);
    $oCur->rate     = @trim($sCur[1]);
    $oCur->dec      = @trim($sCur[2]);
    $oCur->thousand = @trim($sCur[3]);
    $oCur->sign     = @trim($sCur[4]);
    $oCur->decimal  = @trim($sCur[5]);

    // change for US version
    if (isset($sCur[6])) {
        $oCur->side = @trim($sCur[6]);
    }

    return oxRegistry::getLang()->formatCurrency($sValue, $oCur);
}
