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
 * Object registry design pattern implementation. Stores the instances of objects
 */
class oxRegistry
{

    /**
     * Instance array
     *
     * @var array
     */
    protected static $_aInstances = array();

    /**
     * Instance getter. Return existing instance or initializes the new one
     *
     * @param string $sClassName Class name
     *
     * @static
     *
     * @return object
     */
    public static function get($sClassName)
    {
        $sClassName = strtolower($sClassName);
        if (isset(self::$_aInstances[$sClassName])) {
            return self::$_aInstances[$sClassName];
        } else {
            self::$_aInstances[$sClassName] = oxNew($sClassName);

            return self::$_aInstances[$sClassName];
        }
    }

    /**
     * Instance setter
     *
     * @param string $sClassName Class name
     * @param object $oInstance  Object instance
     *
     * @static
     *
     * @return null
     */
    public static function set($sClassName, $oInstance)
    {
        $sClassName = strtolower($sClassName);

        if (is_null($oInstance)) {
            unset(self::$_aInstances[$sClassName]);

            return;
        }

        self::$_aInstances[$sClassName] = $oInstance;
    }

    /**
     * Returns oxConfig instance
     *
     * @static
     *
     * @return oxConfig
     */
    public static function getConfig()
    {
        return self::get("oxConfig");
    }

    /**
     * Returns oxSession instance
     *
     * @static
     *
     * @return oxSession
     */
    public static function getSession()
    {
        return self::get("oxSession");
    }

    /**
     * Returns oxLang instance
     *
     * @static
     *
     * @return oxLang
     */
    public static function getLang()
    {
        return self::get("oxLang");
    }

    /**
     * Returns oxUtils instance
     *
     * @static
     *
     * @return oxUtils
     */
    public static function getUtils()
    {
        return self::get("oxUtils");
    }

    /**
     * Return set instances.
     *
     * @return array
     */
    public static function getKeys()
    {
        return array_keys(self::$_aInstances);
    }
}
