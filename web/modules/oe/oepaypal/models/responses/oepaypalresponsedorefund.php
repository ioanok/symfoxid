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
 * PayPal response class for do refund
 */
class oePayPalResponseDoRefund extends oePayPalResponse
{

    /**
     * Return transaction id.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->_getValue('REFUNDTRANSACTIONID');
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
        return $this->_getValue('REFUNDSTATUS');
    }

    /**
     * Return payment status.
     *
     * @return string
     */
    public function getRefundAmount()
    {
        return $this->_getValue('GROSSREFUNDAMT');
    }

    /**
     * Return currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->_getValue('CURRENCYCODE');
    }
}
