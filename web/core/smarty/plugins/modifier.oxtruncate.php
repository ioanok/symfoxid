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
 * This method replaces existing Smarty function for truncating strings
 * (check Smarty documentation for details). When truncating strings
 * additionally we need to convert &#039;/&quot; entities to '/"
 * and after truncating convert them back.
 *
 * -------------------------------------------------------------
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 *  -------------------------------------------------------------
 *
 * @param string  $sString      String to truncate
 * @param integer $iLength      To length
 * @param string  $sSufix       Truncation mark
 * @param bool    $blBreakWords break words
 * @param bool    $middle       middle ?
 *
 * @return string
 */
function smarty_modifier_oxtruncate($sString, $iLength = 80, $sSufix = '...', $blBreakWords = false, $middle = false)
{
    if ($iLength == 0) {
        return '';
    } elseif ( $iLength > 0 && getStr()->strlen( $sString ) > $iLength ) {
        $iLength -= getStr()->strlen( $sSufix );

        $sString = str_replace( array('&#039;', '&quot;'), array( "'",'"' ), $sString );

        if (!$blBreakWords ) {
            $sString = getStr()->preg_replace( '/\s+?(\S+)?$/', '', getStr()->substr( $sString, 0, $iLength + 1 ) );
        }

        $sString = getStr()->substr( $sString, 0, $iLength ).$sSufix;

        return str_replace( array( "'",'"' ), array('&#039;', '&quot;'), $sString );
    }

    return $sString;
}

/* vim: set expandtab: */

?>
