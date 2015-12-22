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
 * PayPal IPN request validator class.
 */
class oePayPalIPNRequestValidator
{
    /**
     * String PayPal receiver email. It should be same as shop owner credential for PayPal.
     *
     * @var string
     */
    const RECEIVER_EMAIL = 'receiver_email';

    /**
     * Shop owner Email from configuration of PayPal module.
     *
     * @var string
     */
    protected $_sShopOwnerUserName = null;

    /**
     * PayPal response if OK.
     *
     * @var string
     */
    protected $_oPayPalResponse = null;

    /**
     * PayPal request to get email.
     *
     * @var string
     */
    protected $_aPayPalRequest = null;

    /**
     * Set shop owner user name - payPal ID.
     *
     * @param string $sShopOwnerUserName
     */
    public function setShopOwnerUserName($sShopOwnerUserName)
    {
        $this->_sShopOwnerUserName = $sShopOwnerUserName;
    }

    /**
     * get shop owner user name - payPal ID.
     *
     * @return string
     */
    public function getShopOwnerUserName()
    {
        return $this->_sShopOwnerUserName;
    }

    /**
     * Set PayPal response object.
     *
     * @param oePayPalResponseDoVerifyWithPayPal $sPayPalResponse
     */
    public function setPayPalResponse($sPayPalResponse)
    {
        $this->_oPayPalResponse = $sPayPalResponse;
    }

    /**
     * Get PayPal response object.
     *
     * @return oePayPalResponseDoVerifyWithPayPal
     */
    public function getPayPalResponse()
    {
        return $this->_oPayPalResponse;
    }

    /**
     * Set PayPal request array.
     *
     * @param array $sPayPalRequest
     */
    public function setPayPalRequest($sPayPalRequest)
    {
        $this->_aPayPalRequest = $sPayPalRequest;
    }

    /**
     * Get PayPal request array.
     *
     * @return array
     */
    public function getPayPalRequest()
    {
        return $this->_aPayPalRequest;
    }

    /**
     * Returns validation failure messages.
     *
     * @return array
     */
    public function getValidationFailureMessage()
    {
        $aPayPalRequest = $this->getPayPalRequest();
        $oPayPalResponse = $this->getPayPalResponse();
        $sShopOwnerUserName = $this->getShopOwnerUserName();
        $sReceiverEmailPayPal = $aPayPalRequest[self::RECEIVER_EMAIL];

        $aValidationMessage = array(
            'Shop owner'           => (string) $sShopOwnerUserName,
            'PayPal ID'            => (string) $sReceiverEmailPayPal,
            'PayPal ACK'           => ($oPayPalResponse->isPayPalAck() ? 'VERIFIED' : 'NOT VERIFIED'),
            'PayPal Full Request'  => print_r($aPayPalRequest, true),
            'PayPal Full Response' => print_r($oPayPalResponse->getData(), true),
        );

        return $aValidationMessage;
    }

    /**
     * Validate if IPN request from PayPal and to correct shop.
     *
     * @return bool
     */
    public function isValid()
    {
        $aPayPalRequest = $this->getPayPalRequest();
        $oPayPalResponse = $this->getPayPalResponse();
        $sShopOwnerUserName = $this->getShopOwnerUserName();
        $sReceiverEmailPayPal = $aPayPalRequest[self::RECEIVER_EMAIL];

        return ($oPayPalResponse->isPayPalAck() && $sReceiverEmailPayPal == $sShopOwnerUserName);
    }
}
