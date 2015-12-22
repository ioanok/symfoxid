<?php
/**
 * Created by PhpStorm.
 * User: ioan
 * Date: 17.12.2015
 * Time: 11:52 AM
 */

use Symfony\Component\Debug;

/**
 * Class sxSymfonyOxShopControl
 * Extension of oxShopControl OXID core class
 */
class sxSymfonyOxShopControl extends sxSymfonyOxShopControl_parent
{
    /**
     * Sets default exception handler.
     *
     * If shop is in productive mode stick to default OXID exception handler
     * Else register Symfony Debug component's Exception and Error handlers
     *
     * Non-productive eShop mode is intended for eShop installation, configuration, template customization and module debugging phase.
     * As soon as productive mode is turned ON, the cache handling and the error reporting behavior is optimized for the live shop.
     */
    protected function _setDefaultExceptionHandler()
    {
        /**
         * @todo: consider also getEnvironment() function to detect environment
         */
        if (oxRegistry::getConfig()->isProductiveMode()) {
            parent::_setDefaultExceptionHandler();
            return;
        }

        /**
         * Debug::enable() also registers a DebugClassLoader which throw an error because oxid does not care about case when referring to objects
         * symfony is key sensitive:  oxarticlelist != oxArticleList
         */
        //Debug\Debug::enable();
        ini_set('display_errors', 0);
        Debug\ExceptionHandler::register();
        Debug\ErrorHandler::register()->throwAt(0, true);
    }

    /**
     * If shop is in productive mode then call default OXID behavior
     * Else rethrow the exception to symfony debug handler.
     *
     * @param oxException $oEx
     * @throws oxException
     */
    protected function _handleSystemException($oEx)
    {
        if (oxRegistry::getConfig()->isProductiveMode()) {
            parent::_handleSystemException($oEx);
            return;
        }

        throw $oEx;
    }

    /**
     * If shop is in productive mode then call default OXID behavior
     * Else rethrow the exception to symfony debug handler.
     *
     * @param oxException $oEx Exception
     * @throws oxException
     */
    protected function _handleCookieException($oEx)
    {
        if (oxRegistry::getConfig()->isProductiveMode()) {
            parent::_handleCookieException($oEx);
            return;
        }

        throw $oEx;
    }

    /**
     * If shop is in productive mode then call default OXID behavior
     * Else rethrow the exception to symfony debug handler.
     *
     * @param oxException $oEx Exception
     * @throws oxException
     */
    protected function _handleAccessRightsException($oEx)
    {
        if (oxRegistry::getConfig()->isProductiveMode()) {
            parent::_handleAccessRightsException($oEx);
            return;
        }

        throw $oEx;
    }

    /**
     * If shop is in productive mode then call default OXID behavior
     * Else rethrow the exception to symfony debug handler.
     *
     * @param oxConnectionException $oEx message to show on exit
     * @throws oxConnectionException
     */
    protected function _handleDbConnectionException($oEx)
    {
        if (oxRegistry::getConfig()->isProductiveMode()) {
            parent::_handleDbConnectionException($oEx);
            return;
        }

        throw $oEx;
    }

    /**
     * If shop is in productive mode then call default OXID behavior
     * Else rethrow the exception to symfony debug handler.
     *
     * @param oxException $oEx
     * @throws oxException
     */
    protected function _handleBaseException($oEx)
    {
        if (oxRegistry::getConfig()->isProductiveMode()) {
            parent::_handleBaseException($oEx);
            return;
        }

        throw $oEx;
    }
}