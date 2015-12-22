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
 * Purpose: Output multilang string
 * add [{ oxmultilang ident="..." args=... }] where you want to display content
 * ident - language constant
 * args - array of argument that can be parsed to language constant threw %s
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
*/
function smarty_function_oxmultilang( $params, &$smarty )
{
    startProfile("smarty_function_oxmultilang");

    $oLang = oxRegistry::getLang();
    $oConfig = oxRegistry::getConfig();
    $oShop = $oConfig->getActiveShop();
    $blAdmin = $oLang->isAdmin();

    $sIdent  = isset( $params['ident'] ) ? $params['ident'] : 'IDENT MISSING';
    $aArgs = isset( $params['args'] ) ? $params['args'] : false;
    $sSuffix = isset( $params['suffix'] ) ? $params['suffix'] : 'NO_SUFFIX';
    $blShowError = isset( $params['noerror']) ? !$params['noerror'] : true ;

    $iLang = $oLang->getTplLanguage();

    if( !$blAdmin && $oShop->isProductiveMode() ) {
        $blShowError = false;
    }

    try {
        $sTranslation = $oLang->translateString( $sIdent, $iLang, $blAdmin );
        $blTranslationNotFound = !$oLang->isTranslated();
        if ( 'NO_SUFFIX' != $sSuffix ) {
            $sSuffixTranslation = $oLang->translateString( $sSuffix, $iLang, $blAdmin );
        }
    } catch ( oxLanguageException $oEx ) {
        // is thrown in debug mode and has to be caught here, as smarty hangs otherwise!
    }

    if( $blTranslationNotFound && isset( $params['alternative'] ) ) {
        $sTranslation = $params['alternative'];
        $blTranslationNotFound = false;
    }

    if ( !$blTranslationNotFound ) {
        if ( $aArgs !== false ) {
            if ( is_array( $aArgs ) ) {
                $sTranslation = vsprintf( $sTranslation, $aArgs );
            } else {
                $sTranslation = sprintf( $sTranslation, $aArgs );
            }
        }

        if ( 'NO_SUFFIX' != $sSuffix ) {
            $sTranslation .= $sSuffixTranslation;
        }

    } elseif( $blShowError ) {
        $sTranslation = 'ERROR: Translation for '.$sIdent.' not found!';
    }

    stopProfile("smarty_function_oxmultilang");

    return $sTranslation;
}
