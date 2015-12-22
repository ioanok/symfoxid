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
 * Generates Salt for the user password
 *
 */
class oxPasswordSaltGenerator
{

    /**
     * @var oxOpenSSLFunctionalityChecker
     */
    private $_openSSLFunctionalityChecker;

    /**
     * Sets dependencies.
     *
     * @param oxOpenSSLFunctionalityChecker $openSSLFunctionalityChecker
     */
    public function __construct(oxOpenSSLFunctionalityChecker $openSSLFunctionalityChecker)
    {
        $this->_openSSLFunctionalityChecker = $openSSLFunctionalityChecker;
    }

    /**
     * Generates salt. If openssl_random_pseudo_bytes function is not available,
     * than fallback to custom salt generator.
     *
     * @return string
     */
    public function generate()
    {
        if ($this->_getOpenSSLFunctionalityChecker()->isOpenSslRandomBytesGeneratorAvailable()) {
            $sSalt = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $sSalt = $this->_customSaltGenerator();
        }

        return $sSalt;
    }

    /**
     * Gets open SSL functionality checker.
     *
     * @return oxOpenSSLFunctionalityChecker
     */
    protected function _getOpenSSLFunctionalityChecker()
    {
        return $this->_openSSLFunctionalityChecker;
    }

    /**
     * Generates custom salt.
     *
     * @return string
     */
    protected function _customSaltGenerator()
    {
        $sHash = '';
        $sSalt = '';
        for ($i = 0; $i < 32; $i++) {
            $sHash = hash('sha256', $sHash . mt_rand());
            $iPosition = mt_rand(0, 62);
            $sSalt .= $sHash[$iPosition];
        }

        return $sSalt;
    }
}
