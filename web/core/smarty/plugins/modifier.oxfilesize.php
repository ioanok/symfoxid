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
 * Smarty modifier
 * -------------------------------------------------------------
 * Name:     oxfilesize<br>
 * Purpose:  {$var|oxfilesize} Convert integer file size to readable format
 * -------------------------------------------------------------
 *
 * @param int $iSize Integer size value
 *
 * @return string
 */
function smarty_modifier_oxfilesize($iSize)
{
    if ($iSize < 1024) {
        return $iSize. " B";
    }

    $iSize = $iSize/1024;

    if ($iSize < 1024) {
        return sprintf("%.1f KB", $iSize);
    }

    $iSize = $iSize/1024;

    if ($iSize < 1024) {
        return sprintf("%.1f MB", $iSize);
    }

    $iSize = $iSize/1024;

    return sprintf("%.1f GB", $iSize);

}
