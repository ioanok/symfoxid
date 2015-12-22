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
 * Smarty {mailto} function plugin extension, fixes character encoding problem
 *
 * @param array  $aParams  parameters
 * @param Smarty &$oSmarty smarty object
 *
 * @return string
 */
function smarty_function_oxmailto( $aParams, &$oSmarty )
{
    if ( isset( $aParams['encode'] ) && $aParams['encode'] == 'javascript' ) {

        $sAddress = isset( $aParams['address'] ) ? $aParams['address'] : '';
        $sText = $sAddress;

        $aMailParms = array();
        foreach ( $aParams as $sVarName => $sValue ) {
            switch ( $sVarName ) {
                case 'cc':
                case 'bcc':
                case 'followupto':
                    if ( $sValue ) {
                        $aMailParms[] = $sVarName . '=' . str_replace( array( '%40', '%2C' ), array( '@', ',' ), rawurlencode( $sValue ) );
                    }
                    break;
                case 'subject':
                case 'newsgroups':
                    $aMailParms[] = $sVarName . '=' . rawurlencode( $sValue );
                    break;
                case 'extra':
                case 'text':
                    $sName  = "s".ucfirst( $sVarName );
                    $$sName = $sValue;
                default:
            }
        }

        for ( $iCtr = 0; $iCtr < count( $aMailParms ); $iCtr++ ) {
            $sAddress .= ( $iCtr == 0 ) ? '?' : '&';
            $sAddress .= $aMailParms[$iCtr];
        }

        $sString = 'document.write(\'<a href="mailto:'.$sAddress.'" '.$sExtra.'>'.$sText.'</a>\');';
        $sEncodedString = "%".wordwrap( current( unpack( "H*", $sString ) ), 2, "%", true );
        return '<script type="text/javascript">eval(decodeURIComponent(\''.$sEncodedString.'\'))</script>';
    } else {
        include_once $oSmarty->_get_plugin_filepath( 'function', 'mailto' );
        return smarty_function_mailto($aParams, $oSmarty );
    }
}
