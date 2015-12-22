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
 * Factory class responsible for redirecting string handling functions to specific
 * string handling class. String handler basically is intended for dealing with multibyte string
 * and is NOT supposed to replace all string handling functions.
 * We use the handler for shop data and user input, but prefer not to use it for ascii strings
 * (eg. field or file names).
 *
 */
class oxStr
{

    /**
     * Specific string handler
     *
     * @var object
     */
    static protected $_oHandler;

    /**
     * Class constructor. The constructor is defined in order to be possible to call parent::__construct() in modules.
     *
     * @return null;
     */
    public function __construct()
    {
    }

    /**
     * Static method initializing new string handler or returning the existing one.
     *
     * @return object
     */
    public static function getStr()
    {
        if (!isset(self::$_oHandler)) {
            //let's init now non-static instance of oxStr to get the instance of str handler
            self::$_oHandler = oxNew("oxStr")->_getStrHandler();
        }

        return self::$_oHandler;
    }

    /**
     * Non static getter returning str handler. The sense of getStr() and _getStrHandler() is
     * to be possible to call this method statically ( oxStr::getStr() ), yet leaving the
     * possibility to extend it in modules by overriding _getStrHandler() method.
     *
     * @return object
     */
    protected function _getStrHandler()
    {
        if (oxRegistry::getConfig()->isUtf() && function_exists('mb_strlen')) {
            return oxNew("oxStrMb");
        }

        return oxNew("oxStrRegular");
    }
}
