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
 * PayPal request builder class
 */
class oePayPalPayPalRequestBuilder
{
    /**
     * Request object
     *
     * @var oePayPalPayPalRequest
     */
    protected $_oRequest = null;

    /**
     * Sets Authorization id
     *
     * @param string $sAuthorizationId
     */
    public function setAuthorizationId($sAuthorizationId)
    {
        $this->getRequest()->setParameter('AUTHORIZATIONID', $sAuthorizationId);
    }

    /**
     * Sets Transaction id
     *
     * @param string $sTransactionId
     */
    public function setTransactionId($sTransactionId)
    {
        $this->getRequest()->setParameter('TRANSACTIONID', $sTransactionId);
    }

    /**
     * Set amount
     *
     * @param double $dAmount
     * @param string $sCurrencyCode
     */
    public function setAmount($dAmount, $sCurrencyCode = null)
    {
        $this->getRequest()->setParameter('AMT', $dAmount);
        if (!$sCurrencyCode) {
            $sCurrencyCode = oxRegistry::getConfig()->getActShopCurrencyObject()->name;
        }
        $this->getRequest()->setParameter('CURRENCYCODE', $sCurrencyCode);
    }

    /**
     * Set Capture type
     *
     * @param string $sType
     */
    public function setCompleteType($sType)
    {
        $this->getRequest()->setParameter('COMPLETETYPE', $sType);
    }

    /**
     * Set Refund type
     *
     * @param string $sType
     */
    public function setRefundType($sType)
    {
        $this->getRequest()->setParameter('REFUNDTYPE', $sType);
    }

    /**
     * Set Refund type
     *
     * @param string $sComment
     */
    public function setComment($sComment)
    {
        $this->getRequest()->setParameter('NOTE', $sComment);
    }


    /**
     * Return request object.
     *
     * @return oePayPalPayPalRequest
     */
    public function getRequest()
    {
        if ($this->_oRequest === null) {
            $this->_oRequest = oxNew('oePayPalPayPalRequest');
        }

        return $this->_oRequest;
    }

    /**
     * Sets Request object.
     *
     * @param oePayPalPayPalRequest $oRequest
     */
    public function setRequest($oRequest)
    {
        $this->_oRequest = $oRequest;
    }
}
