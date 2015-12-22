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
abstract class oePayPalOrderAction
{
    /**
     *
     * @var oePayPalRequest
     */
    protected $_oOrder = null;

    /**
     * @var string
     */
    protected $_sOrderStatus = null;

    /**
     * @var oePayPalOrderCaptureActionHandler
     */
    protected $_oHandler = null;

    /**
     * Sets handler and order.
     *
     * @param oePayPalOrderCaptureActionHandler $oHandler
     * @param oePayPalRequest                   $oOrder
     */
    public function __construct($oHandler, $oOrder)
    {
        $this->_oHandler = $oHandler;
        $this->_oOrder = $oOrder;
    }

    /**
     * Returns oePayPalOrderCaptureActionHandler object.
     *
     * @return oePayPalOrderCaptureActionHandler
     */
    public function getHandler()
    {
        return $this->_oHandler;
    }

    /**
     * Returns oePayPalPayPalOrder object.
     *
     * @return oePayPalPayPalOrder
     */
    public function getOrder()
    {
        return $this->_oOrder;
    }

    /**
     * Returns formatted date
     *
     * @return string
     */
    public function getDate()
    {
        return date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime());
    }

    /**
     * Processes PayPal action
     */
    abstract public function process();
}
