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
 * PayPal request class
 */
class oePayPalPayPalRequest
{
    /**
     * PayPal response data
     *
     * @var array
     */
    protected $_aData = array();

    /**
     * Sets value to data by given key.
     *
     * @param string $sKey   Key of data value.
     * @param string $sValue Data value.
     */
    public function setParameter($sKey, $sValue)
    {
        $this->_aData[$sKey] = $sValue;
    }

    /**
     * Returns value by given key.
     *
     * @param string $sKey Key of data value.
     *
     * @return string
     */
    public function getParameter($sKey)
    {
        return $this->_aData[$sKey];
    }

    /**
     * Set request data.
     *
     * @param array $aResponseData Response data from PayPal.
     */
    public function setData($aResponseData)
    {
        $this->_aData = $aResponseData;
    }

    /**
     * Return request data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->_aData;
    }

    /**
     * Return value from data by given key.
     *
     * @param string $sKey   Key of data value.
     * @param string $sValue Data value.
     */
    protected function _setValue($sKey, $sValue)
    {
        $this->_aData[$sKey] = $sValue;
    }
}
