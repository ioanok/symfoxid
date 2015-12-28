<?php
/**
 * Created by PhpStorm.
 * User: ioan
 * Date: 17.12.2015
 * Time: 11:52 AM
 */

use Symfony\Component\Debug;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class sxSymfonyOxShopControl
 * Extension of oxShopControl OXID core class
 */
class sxSymfonyOxShopControl extends sxSymfonyOxShopControl_parent
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Main shop manager, that sets shop status, executes configuration methods.
     * Executes oxShopControl::_runOnce(), if needed sets default class (according
     * to admin or regular activities). Additionally its possible to pass class name,
     * function name and parameters array to view, which will be executed.
     *
     * @param string $sClass      Class name
     * @param string $sFunction   Function name
     * @param array  $aParams     Parameters array
     * @param array  $aViewsChain Array of views names that should be initialized also
     */
    public function start($sClass = null, $sFunction = null, $aParams = null, $aViewsChain = null)
    {
        $this->registerContainerBuilder();
        //$this->registerExtensionLoader();

        parent::start($sClass, $sFunction, $aParams, $aViewsChain);
    }

    /**
     * Adds Symfony ContainerBuilder to OXID registry
     */
    private function registerContainerBuilder()
    {
        oxRegistry::set('container', new ContainerBuilder());
    }

    private function registerExtensionLoader()
    {
        $extensionLoader = new ExtensionLoader();
        $extensionLoader->setContainer($this->getContainer());
        $this->getContainer()->set('as.extension_loader', $extensionLoader);
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        if ($this->container === null) {
            $this->container = \oxRegistry::get('container');
        }
        return $this->container;
    }




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