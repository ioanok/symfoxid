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
 * PayPal request builder class for get express checkout details
 */
class oePayPalGetExpressCheckoutDetailsRequestBuilder
{
    /**
     * PayPal Request
     *
     * @var oePayPalPayPalRequest
     */
    protected $_oPayPalRequest = null;

    /**
     * Session object
     *
     * @var oxSession
     */
    protected $_oSession = null;

    /**
     * Sets PayPal request object.
     *
     * @param oePayPalPayPalRequest $oRequest
     */
    public function setPayPalRequest($oRequest)
    {
        $this->_oPayPalRequest = $oRequest;
    }

    /**
     * Returns PayPal request object.
     *
     * @return oePayPalPayPalRequest
     */
    public function getPayPalRequest()
    {
        if ($this->_oPayPalRequest === null) {
            $this->_oPayPalRequest = oxNew('oePayPalPayPalRequest');
        }

        return $this->_oPayPalRequest;
    }

    /**
     * Sets Session.
     *
     * @param oxSession $oSession
     */
    public function setSession($oSession)
    {
        $this->_oSession = $oSession;
    }

    /**
     * Returns Session.
     *
     * @return oxSession
     *
     * @throws oePayPalMissingParameterException
     */
    public function getSession()
    {
        if (!$this->_oSession) {
            /**
             * @var oePayPalMissingParameterException $oException
             */
            $oException = oxNew('oePayPalMissingParameterException');
            throw $oException;
        }

        return $this->_oSession;
    }

    /**
     * Builds Request.
     *
     * @return oePayPalPayPalRequest
     */
    public function buildRequest()
    {
        $oRequest = $this->getPayPalRequest();
        $oRequest->setParameter('TOKEN', $this->getSession()->getVariable('oepaypal-token'));

        return $oRequest;
    }
}
