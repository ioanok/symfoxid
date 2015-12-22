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
 * PayPal order action factory class
 */
class oePayPalOrderActionData
{

    /**
     * Request object
     *
     * @var oePayPalRequest
     */
    protected $_oRequest = null;

    /**
     * Order object
     *
     * @var oePayPalPayPalOrder
     */
    protected $_oOrder = null;

    /**
     * Sets dependencies.
     *
     * @param oePayPalRequest     $oRequest
     * @param oePayPalPayPalOrder $oOrder
     */
    public function __construct($oRequest, $oOrder)
    {
        $this->_oRequest = $oRequest;
        $this->_oOrder = $oOrder;
    }

    /**
     * Returns Request object
     *
     * @return oePayPalRequest
     */
    public function getRequest()
    {
        return $this->_oRequest;
    }

    /**
     * Returns PayPal Order object
     *
     * @return oePayPalRequest
     */
    public function getOrder()
    {
        return $this->_oOrder;
    }

    /**
     * returns action amount
     *
     * @return string
     */
    public function getAuthorizationId()
    {
        return $this->getOrder()->oxorder__oxtransid->value;
    }

    /**
     * returns comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->getRequest()->getRequestParameter('action_comment');
    }

    /**
     * Returns order status
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->getRequest()->getRequestParameter('order_status');
    }
}
