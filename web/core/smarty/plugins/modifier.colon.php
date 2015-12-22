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
 * Smarty colon modifier plugin
 *
 * Type:     modifier<br>
 * Name:     colon<br>
 * Date:     Mar 12 2013
 * Purpose:  Add simple or specific colon
 * Input:    string to add colon to
 * Example:  [{assign var="variable" value="TRANSLATION_INDENT"|oxmultilangassign|colon}]
 * TRANSLATION_INDENT = 'translation' COLON = ' :', $variable = 'translation :'
 *
 * @param string $string String to add colon to.
 *
 * @return string
 */
function smarty_modifier_colon($string)
{
    $colon = oxRegistry::getLang()->translateString('COLON');

    return $string . $colon;
}
