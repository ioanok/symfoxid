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
 * Static class mostly containing static methods which are supposed to be called before the full framework initialization
 */
class Oxid
{

    /**
     * Executes main shop controller
     *
     * @static
     *
     * @return void
     */
    public static function run()
    {
        $oShopControl = oxNew('oxShopControl');

        return $oShopControl->start();
    }

    /**
     * Executes shop widget controller
     *
     * @static
     *
     * @return void
     */
    public static function runWidget()
    {
        $oWidgetControl = oxNew('oxWidgetControl');

        return $oWidgetControl->start();
    }
}
