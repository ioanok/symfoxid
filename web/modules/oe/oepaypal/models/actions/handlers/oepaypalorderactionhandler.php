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
 * PayPal order action class
 */
abstract class oePayPalOrderActionHandler
{
    /**
     * @var object
     */
    protected $_oData = null;

    /**
     * @var oePayPalService
     */
    protected $_oPayPalService = null;

    /**
     * PayPal order
     *
     * @var oePayPalPayPalOrder
     */
    protected $_oPayPalRequestBuilder = null;

    /**
     * Sets data object.
     *
     * @param object $oData
     */
    public function __construct($oData)
    {
        $this->_oData = $oData;
    }

    /**
     * Returns Data object
     *
     * @return object
     */
    public function getData()
    {
        return $this->_oData;
    }

    /**
     * Sets PayPal request builder
     *
     * @param oePayPalPayPalRequestBuilder $oBuilder
     */
    public function setPayPalRequestBuilder($oBuilder)
    {
        $this->_oPayPalRequestBuilder = $oBuilder;
    }

    /**
     * Returns PayPal request builder
     *
     * @return oePayPalPayPalRequestBuilder
     */
    public function getPayPalRequestBuilder()
    {
        if ($this->_oPayPalRequestBuilder === null) {
            $this->_oPayPalRequestBuilder = oxNew('oePayPalPayPalRequestBuilder');
        }

        return $this->_oPayPalRequestBuilder;
    }

    /**
     * Sets PayPal service
     *
     * @param oePayPalService $oService
     */
    public function setPayPalService($oService)
    {
        $this->_oPayPalService = $oService;
    }

    /**
     * Returns PayPal service
     *
     * @return oePayPalService
     */
    public function getPayPalService()
    {
        if ($this->_oPayPalService === null) {
            $this->_oPayPalService = oxNew('oePayPalService');
        }

        return $this->_oPayPalService;
    }
}
