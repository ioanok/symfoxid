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
 * PayPal order action void class.
 */
class oePayPalOrderVoidActionHandler extends oePayPalOrderActionHandler
{
    /**
     * PayPal Request.
     *
     * @var oePayPalRequest
     */
    protected $_oPayPalRequest = null;

    /**
     * Returns PayPal response; initiates if not set.
     *
     * @return mixed
     */
    public function getPayPalResponse()
    {
        $oService = $this->getPayPalService();
        $oRequest = $this->getPayPalRequest();

        return $oService->doVoid($oRequest);
    }

    /**
     * Returns PayPal request; initiates if not set.
     *
     * @return oePayPalPayPalRequest
     */
    public function getPayPalRequest()
    {
        if (is_null($this->_oPayPalRequest)) {
            $oRequestBuilder = $this->getPayPalRequestBuilder();

            $oData = $this->getData();

            $oRequestBuilder->setAuthorizationId($oData->getAuthorizationId());
            $oRequestBuilder->setAmount($oData->getAmount(), $oData->getCurrency());
            $oRequestBuilder->setComment($oData->getComment());

            $this->_oPayPalRequest = $oRequestBuilder->getRequest();
        }

        return $this->_oPayPalRequest;
    }

    /**
     * Sets PayPal request.
     *
     * @param oePayPalRequest $oPayPalRequest Request object.
     */
    public function setPayPalRequest($oPayPalRequest)
    {
        $this->_oPayPalRequest = $oPayPalRequest;
    }
}
