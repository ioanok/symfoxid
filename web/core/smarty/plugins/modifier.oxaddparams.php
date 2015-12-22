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
 * Purpose: add additional parameters to SEO url
 * add |oxaddparams:"...." to link
 * -------------------------------------------------------------
 *
 * @param string $sUrl       Url
 * @param string $sDynParams Dynamic URL parameters
 *
 * @return string
 */
function smarty_modifier_oxaddparams( $sUrl, $sDynParams )
{
    $oStr = getStr();
    // removing empty parameters
    $sDynParams = $sDynParams?$oStr->preg_replace( array( '/^\?/', '/^\&(amp;)?$/' ), '', $sDynParams ):false;
    if ( $sDynParams ) {
        $sUrl .= ( ( strpos( $sUrl, '?' ) !== false ) ? "&amp;":"?" ) . $sDynParams;
    }
    return oxRegistry::get("oxUtilsUrl")->processSeoUrl( $sUrl );
}
