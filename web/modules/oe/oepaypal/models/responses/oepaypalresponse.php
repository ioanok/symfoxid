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
 */

/**
 * Abstract PayPal Response class
 */
abstract class oePayPalResponse
{
    /**
     * PayPal response data
     *
     * @var array
     */
    protected $_aData = null;

    /**
     * Set response data
     *
     * @param array $aResponseData Response data from PayPal
     */
    public function setData($aResponseData)
    {
        $this->_aData = $aResponseData;
    }

    /**
     * Return response data
     *
     * @return array
     */
    public function getData()
    {
        return $this->_aData;
    }

    /**
     * Return value from data by given key
     *
     * @param sting $sKey key of data value
     *
     * @return string
     */
    protected function _getValue($sKey)
    {
        $aData = $this->getData();

        return $aData[$sKey];
    }
}
