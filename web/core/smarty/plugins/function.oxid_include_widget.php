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
 * Smarty function
 * -------------------------------------------------------------
 * Purpose: set params and render widget
 * Use [{ oxid_include_dynamic file="..." }] instead of include
 * -------------------------------------------------------------
 *
 * @param array  $params   params
 * @param Smarty &$oSmarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxid_include_widget($params, &$oSmarty)
{
    $myConfig = oxRegistry::getConfig();
    $blNoScript = ($params['noscript']?$params['noscript']:false);
    $sClass     = strtolower($params['cl']);
    $params['cl'] = $sClass;
    $aParentViews = null;


    unset($params['cl']);

    $aParentViews = null;

    if ( !empty($params["_parent"]) ) {
        $aParentViews = explode("|", $params["_parent"]);
        unset( $params["_parent"] );
    }

    /** @var oxWidgetControl $oShopControl */
    $oShopControl = oxRegistry::get('oxWidgetControl');

    return $oShopControl->start( $sClass, null, $params, $aParentViews );
}
