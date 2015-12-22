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
 * PayPal IPN Payment validator class.
 */
class oePayPalIPNPaymentValidator
{
    /**
     * Language object to get translations from.
     *
     * @var object
     */
    protected $_oLang = null;

    /**
     * Payment created from PayPal request.
     *
     * @var oePayPalOrderPayment
     */
    protected $_oRequestPayment = null;

    /**
     * Payment created by PayPal request id.
     *
     * @var oePayPalOrderPayment
     */
    protected $_oOrderPayment = null;

    /**
     * Sets language object to get translations from.
     *
     * @param object $oLang get translations from.
     */
    public function setLang($oLang)
    {
        $this->_oLang = $oLang;
    }

    /**
     * Gets language object to get translations from.
     *
     * @return object
     */
    public function getLang()
    {
        return $this->_oLang;
    }

    /**
     * Sets request object.
     *
     * @param oePayPalOrderPayment $oRequestPayment
     */
    public function setRequestOrderPayment($oRequestPayment)
    {
        $this->_oRequestPayment = $oRequestPayment;
    }

    /**
     * Returns request payment object.
     *
     * @return oePayPalOrderPayment
     */
    public function getRequestOrderPayment()
    {
        return $this->_oRequestPayment;
    }

    /**
     * Sets order payment object.
     *
     * @param oePayPalOrderPayment $oPayment
     */
    public function setOrderPayment($oPayment)
    {
        $this->_oOrderPayment = $oPayment;
    }

    /**
     * Returns order payment object.
     *
     * @return oePayPalOrderPayment
     */
    public function getOrderPayment()
    {
        return $this->_oOrderPayment;
    }

    /**
     * Returns validation failure message.
     *
     * @return string
     */
    public function getValidationFailureMessage()
    {
        $oRequestPayment = $this->getRequestOrderPayment();
        $oOrderPayment = $this->getOrderPayment();

        $sCurrencyPayPal = $oRequestPayment->getCurrency();
        $dPricePayPal = $oRequestPayment->getAmount();

        $sCurrencyPayment = $oOrderPayment->getCurrency();
        $dAmountPayment = $oOrderPayment->getAmount();

        $oLang = $this->getLang();
        $sValidationMessage = $oLang->translateString('OEPAYPAL_PAYMENT_INFORMATION') . ': ' . $dAmountPayment . ' ' . $sCurrencyPayment . '. ' . $oLang->translateString('OEPAYPAL_INFORMATION') . ': ' . $dPricePayPal . ' ' . $sCurrencyPayPal . '.';

        return $sValidationMessage;
    }

    /**
     * Check if PayPal response fits payment information.
     *
     * @return bool
     */
    public function isValid()
    {
        $oRequestPayment = $this->getRequestOrderPayment();
        $oOrderPayment = $this->getOrderPayment();

        $sCurrencyPayPal = $oRequestPayment->getCurrency();
        $dPricePayPal = $oRequestPayment->getAmount();

        $sCurrencyPayment = $oOrderPayment->getCurrency();
        $dAmountPayment = $oOrderPayment->getAmount();

        return ($sCurrencyPayPal == $sCurrencyPayment && $dPricePayPal == $dAmountPayment);
    }
}
