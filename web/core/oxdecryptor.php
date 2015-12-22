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
 * Class oxDecryptor
 */
class oxDecryptor
{

    /**
     * Decrypts string with given key.
     *
     * @param string $sString string
     * @param string $sKey    key
     *
     * @return string
     */
    public function decrypt($sString, $sKey)
    {
        $sKey = $this->_formKey($sKey, $sString);

        $sString = substr($sString, 3);
        $sString = str_replace('!', '=', $sString);
        $sString = base64_decode($sString);
        $sString = $sString ^ $sKey;

        return substr($sString, 2, -2);
    }

    /**
     * Forms key for use in encoding.
     *
     * @param string $sKey
     * @param string $sString
     *
     * @return string
     */
    protected function _formKey($sKey, $sString)
    {
        $sKey = '_' . $sKey;
        $iKeyLength = (strlen($sString) / strlen($sKey)) + 5;

        return str_repeat($sKey, $iKeyLength);
    }
}
