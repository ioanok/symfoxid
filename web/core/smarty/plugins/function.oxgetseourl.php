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
 * Purpose: output SEO style url
 * add [{ oxgetseourl ident="..." }] where you want to display content
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxgetseourl( $params, &$smarty )
{
    $sOxid = isset( $params['oxid'] ) ? $params['oxid'] : null;
    $sType = isset( $params['type'] ) ? $params['type'] : null;
    $sUrl  = $sIdent = isset( $params['ident'] ) ? $params['ident'] : null;

    // requesting specified object SEO url
    if ( $sType ) {
        $oObject = oxNew( $sType );

        // special case for content type object when ident is provided
        if ( $sType == 'oxcontent' && $sIdent && $oObject->loadByIdent( $sIdent ) ) {
            $sUrl = $oObject->getLink();
        } elseif ( $sOxid ) {
            //minimising aricle object loading
            if ( strtolower($sType) == "oxarticle") {
                $oObject->disablePriceLoad();
                $oObject->setNoVariantLoading( true );
            }

            if ( $oObject->load( $sOxid ) ) {
                $sUrl = $oObject->getLink();
            }
        }
    } elseif ( $sUrl && oxRegistry::getUtils()->seoIsActive() ) {
        // if SEO is on ..
        $oEncoder = oxRegistry::get("oxSeoEncoder");
        if ( ( $sStaticUrl = $oEncoder->getStaticUrl( $sUrl ) ) ) {
            $sUrl = $sStaticUrl;
        } else {
            // in case language parameter is not added to url
            $sUrl = oxRegistry::get("oxUtilsUrl")->processUrl( $sUrl );
        }
    }

    $sDynParams = isset( $params['params'] )?$params['params']:false;
    if ( $sDynParams ) {
        include_once $smarty->_get_plugin_filepath( 'modifier', 'oxaddparams' );
        $sUrl = smarty_modifier_oxaddparams( $sUrl, $sDynParams );
    }

    return $sUrl;
}
