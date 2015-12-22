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
 * Smarty wordwrap modifier
 * -------------------------------------------------------------
 * Name:     wordwrap<br>
 * Purpose:  wrap a string of text at a given length
 * -------------------------------------------------------------
 *
 * @param string  $sString String to wrap
 * @param integer $iLength To length
 * @param string  $sWraper wrap using
 * @param bool    $blCut   Cut
 *
 * @return string
 */
function smarty_modifier_oxwordwrap($sString, $iLength=80, $sWraper="\n", $blCut=false)
{
    return getStr()->wordwrap($sString, $iLength, $sWraper, $blCut);
}

?>
