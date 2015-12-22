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
 * Purpose: Modifies provided language constant with it's translation
 * usage: [{ $val|oxmultilangassign}]
 * -------------------------------------------------------------
 *
 * @param string $sIdent language constant ident
 * @param mixed  $args   for constants using %s notations
 *
 * @return string
 */
function smarty_modifier_oxmultilangassign( $sIdent, $args = null )
{
    if ( !isset( $sIdent ) ) {
        $sIdent = 'IDENT MISSING';
    }

    $oLang = oxRegistry::getLang();
    $oConfig = oxRegistry::getConfig();
    $oShop = $oConfig->getActiveShop();
    $iLang = $oLang->getTplLanguage();
    $blShowError = true;

    if( $oShop->isProductiveMode() ) {
        $blShowError = false;
    }

    try {
        $sTranslation = $oLang->translateString( $sIdent, $iLang, $oLang->isAdmin() );
        $blTranslationNotFound = !$oLang->isTranslated();
    } catch ( oxLanguageException $oEx ) {
        // is thrown in debug mode and has to be caught here, as smarty hangs otherwise!
    }

    if(!$blTranslationNotFound){
        if ( $args ) {
            if ( is_array( $args ) ) {
                $sTranslation = vsprintf( $sTranslation, $args );
            } else {
                $sTranslation = sprintf( $sTranslation, $args );
            }
        }
    } elseif ($blShowError) {
        $sTranslation = 'ERROR: Translation for '.$sIdent.' not found!';
    }

    return $sTranslation;
}
