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
 * Add custom functions here.
 */

/**
 * Simulate dev or prod environment in development
 * Set in .htaccess:
 *      SetEnv ENV "dev" or
 *      SetEnv ENV "prod"
 * Environment is retrieved with $_SERVER['REDIRECT_ENV']
 *
 * @return string
 */
function getEnvironment()
{
    return getenv( 'ENV' ) ? : getenv( 'REDIRECT_ENV' ) ? : 'prod';
}

if (!function_exists('registerComposerAutoLoad')) {
    /**
     * Registers auto-loader from composer.
     */
    function registerComposerAutoLoad()
    {
        // sx: Register sologics composer autoloader
        $autoloaderPath = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($autoloaderPath)) {
            include_once $autoloaderPath;
        }

        // initialize symfony kernel
        require_once __DIR__ . '/../kernelbootstrap.php';
    }
}