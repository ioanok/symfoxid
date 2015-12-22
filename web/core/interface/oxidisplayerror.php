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
 * DisplayError interface
 *
 */
interface oxIDisplayError
{

    /**
     * This method should return a localized message for displaying
     *
     * @return a string to display to the user
     */
    public function getOxMessage();

    /**
     * Returns a type of the error, e.g. the class of the exception or whatever class
     * implemented this interface
     *
     * @return String of Error Type
     */
    public function getErrorClassType();

    /**
     * Possibility to access additional values
     *
     * @param string $sName Value name
     *
     * @return an additional value (string) by its name
     */
    public function getValue($sName);
}
