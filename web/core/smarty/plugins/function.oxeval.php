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
 * Purpose: eval given string
 * add [{ oxeval var="..." }] where you want to display content
 * -------------------------------------------------------------
 *
 * @param array  $aParams  parameters to process
 * @param smarty &$oSmarty smarty object
 *
 * @return string
 */
function smarty_function_oxeval( $aParams, &$oSmarty )
{
    if ( $aParams['var'] && ( $aParams['var'] instanceof oxField ) ) {
        $aParams['var'] = trim($aParams['var']->getRawValue());
    }

    // processign only if enabled
    if ( oxRegistry::getConfig()->getConfigParam( 'bl_perfParseLongDescinSmarty' ) || isset( $aParams['force'] ) ) {
        include_once $oSmarty->_get_plugin_filepath( 'function', 'eval' );
        return smarty_function_eval( $aParams, $oSmarty );
    }

    return $aParams['var'];
}
