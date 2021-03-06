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
 * Class is responsible for openSSL functionality availability checking.
 */
class oxOpenSSLFunctionalityChecker
{

    /**
     * Checks if openssl_random_pseudo_bytes function is available.
     *
     * @return bool
     */
    public function isOpenSslRandomBytesGeneratorAvailable()
    {
        return function_exists('openssl_random_pseudo_bytes');
    }
}
