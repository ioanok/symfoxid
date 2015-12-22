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
 * File: insert.oxid_nocache.php
 * Type: string, html
 * Name: oxid_nocache
 * Purpose: Inserts Items not cached
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_insert_oxid_nocache($params, &$smarty)
{   $myConfig = oxRegistry::getConfig();

    $smarty->caching = false;

   /* if( isset( $smarty->oxobject->oProduct))
        $smarty->assign_by_ref( "product", $smarty->oxobject->oProduct);*/

    // #1184M - specialchar search
    $sSearchParamForHTML = oxRegistry::getConfig()->getRequestParameter("searchparam");
    $sSearchParamForLink = rawurlencode( oxRegistry::getConfig()->getRequestParameter( "searchparam", true ) );
    if ( $sSearchParamForHTML ) {
        $smarty->assign_by_ref( "searchparamforhtml", $sSearchParamForHTML );
        $smarty->assign_by_ref( "searchparam", $sSearchParamForLink );
    }

    $sSearchCat = oxRegistry::getConfig()->getRequestParameter("searchcnid");
    if( $sSearchCat )
        $smarty->assign_by_ref( "searchcnid", rawurldecode( $sSearchCat ) );

    foreach (array_keys( $params) as $key) {
        $viewData = & $params[$key];
        $smarty->assign_by_ref($key, $viewData);
    }

    $sOutput = $smarty->fetch( $params['tpl']);

    $smarty->caching = false;

    return $sOutput;
}
