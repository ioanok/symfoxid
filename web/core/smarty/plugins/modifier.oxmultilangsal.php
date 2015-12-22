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
 * Purpose: Output translated salutation field
 * add [{ $ }] where you want to display content
 * -------------------------------------------------------------
 *
 * @param string $sIdent language constant ident
 *
 * @return string
 */
function smarty_modifier_oxmultilangsal( $sIdent )
{
    $oLang = oxRegistry::getLang();
    $iLang = $oLang->getTplLanguage();

    if ( !isset( $iLang ) ) {
        $iLang = $oLang->getBaseLanguage();
        if ( !isset( $iLang ) ) {
            $iLang = 0;
        }
    }

    try {
        $sTranslation = $oLang->translateString( $sIdent, $iLang, $oLang->isAdmin() );
    } catch ( oxLanguageException $oEx ) {
        // is thrown in debug mode and has to be caught here, as smarty hangs otherwise!
    }

    return $sTranslation;
}
