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
 * Smarty plugin
 * -------------------------------------------------------------
 * File: insert.oxid_cmpbasket.php
 * Type: string, html
 * Name: oxid_cmplogin
 * Purpose: Inserts OXID eShop Login without caching
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_insert_oxid_cmplogin($params, &$smarty)
{
    $smarty->caching = false;

    $sOutput = $smarty->fetch( $params['tpl']);

    $smarty->caching = false;

    return $sOutput;
}
