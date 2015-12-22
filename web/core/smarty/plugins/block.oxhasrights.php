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
 * Smarty {oxhasrights}{/oxhasrights} block plugin
 *
 * Type:     block function<br>
 * Name:     oxhasrights<br>
 * Purpose:  checks if user has rights to view block of data
 *
 * @param array  $params  params
 * @param string $content contents of the block
 * @param Smarty &$smarty clever simulation of a method
 * @param bool   &$repeat repeat
 *
 * @return string $content re-formatted
 */
function smarty_block_oxhasrights( $params, $content, &$smarty, &$repeat )
{
    return $content;

}
