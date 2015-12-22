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
class oePayPalOrderRefundActionData extends oePayPalOrderActionData
{

    /**
     * @var oePayPalOrderPayment
     */
    public $_oPaymentBeingRefunded = null;

    /**
     * Returns action type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getRequest()->getRequestParameter('refund_type');
    }

    /**
     * Returns action amount.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getRequest()->getRequestParameter('transaction_id');
    }

    /**
     * Returns amount to refund.
     *
     * @return float
     */
    public function getAmount()
    {
        $dAmount = $this->getRequest()->getRequestParameter('refund_amount');

        return $dAmount ? $dAmount : $this->getPaymentBeingRefunded()->getRemainingRefundAmount();
    }

    /**
     * Returns currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getOrder()->getPayPalOrder()->getCurrency();
    }

    /**
     * Returns payment to refund.
     *
     * @return float
     */
    public function getPaymentBeingRefunded()
    {
        if (is_null($this->_oPaymentBeingRefunded)) {
            $this->_oPaymentBeingRefunded = oxNew("oePayPalOrderPayment");
            $this->_oPaymentBeingRefunded->loadByTransactionId($this->getTransactionId());
        }

        return $this->_oPaymentBeingRefunded;
    }
}
