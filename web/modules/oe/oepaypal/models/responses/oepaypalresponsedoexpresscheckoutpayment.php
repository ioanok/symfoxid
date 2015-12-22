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
 * PayPal response class for do express checkout payment
 */
class oePayPalResponseDoExpressCheckoutPayment extends oePayPalResponse
{

    /**
     * Return transaction id.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->_getValue('PAYMENTINFO_0_TRANSACTIONID');
    }

    /**
     * Return transaction id.
     *
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->_getValue('CORRELATIONID');
    }

    /**
     * Return payment status.
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->_getValue('PAYMENTINFO_0_PAYMENTSTATUS');
    }

    /**
     * Return price amount.
     *
     * @return string
     */
    public function getAmount()
    {
        return ( float ) $this->_getValue('PAYMENTINFO_0_AMT');
    }

    /**
     * Return currency code.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->_getValue('PAYMENTINFO_0_CURRENCYCODE');
    }
}
