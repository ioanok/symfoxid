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
 * Class oxEncryptor
 */
class oxEncryptor
{

    /**
     * Encrypts string with given key.
     *
     * @param string $sString
     * @param string $sKey
     *
     * @return string
     */
    public function encrypt($sString, $sKey)
    {
        $sString = "ox{$sString}id";

        $sKey = $this->_formKey($sKey, $sString);

        $sString = $sString ^ $sKey;
        $sString = base64_encode($sString);
        $sString = str_replace("=", "!", $sString);

        return "ox_$sString";
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
