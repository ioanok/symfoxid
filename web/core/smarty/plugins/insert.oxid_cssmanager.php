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
 * File: insert.oxid_cssmanager.php
 * Type: string, html
 * Name: oxid_cmpbasket
 * Purpose: Includes css style file according to template file or sets default
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_insert_oxid_cssmanager($params, &$smarty)
{   $myConfig = oxRegistry::getConfig();

    $smarty->caching = false;

    // template file name
    $sTplName = $smarty->oxobject->getTemplateName();

    // css file extension
    $sCssExt  = "css";

    // sets name of alternative CSS file passed template parameters
    if ( isset($params["cssname"]) && $params["cssname"]) {
        $sAltCss = $params["cssname"];
    // possible CSS file for current template
    } else {
        $sAltCss = $sTplName . "." . $sCssExt;
    }

    // user defined alternative CSS files dir
    $sAltCssDir = "styles/";

    // URL to templates, there may be stored and css files
    if ( isset($params["cssurl"]) && $params["cssurl"]) {
        $sTplURL = $params["cssurl"];
    } else {
        $sTplURL =  $myConfig->getResourceUrl( $sAltCssDir, isAdmin() );
    }

    // direct path to templates, there may be stored and css files
    if ( isset($params["csspath"]) && $params["csspath"]) {
        $sTplPath = $params["csspath"];
    } else {
        $sTplPath = $myConfig->getResourcePath( $sAltCssDir, isAdmin() );
    }

    // full path to alternavive CSS file
    $sAltFullPath = $sTplPath . $sAltCss;

    $sOutput = "";
    // checking if alternative CSS file exists and returning URL to CSS file
    if ( $sTplName && file_exists( $sAltFullPath) && is_file( $sAltFullPath)) {
        $sOutput = '<link rel="stylesheet" href="'.$sTplURL . $sAltCss.'">';
    }

    $smarty->caching = false;

    return $sOutput;
}
