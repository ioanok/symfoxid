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
 * Adding template identifier. See config.inc.php in order to turn this functionality on.
 *
 * @param string $sSource          Incoming source
 * @param object &$oSmartyCompiler smarty compiler instance
 *
 * @return string
 */
function smarty_prefilter_oxtpldebug($sSource, &$oSmartyCompiler)
{
    $sTplName = $oSmartyCompiler->_current_file;

    $sOut = "<div style='position: absolute; z-index:9999;color:white;background: #789;
                 padding:0 15px 0 15px'>" .
            $sTplName . "</div><!-- $sTplName template start -->"
            . $sSource .
            "<!-- $sTplName template end -->";

    return $sOut;
}
