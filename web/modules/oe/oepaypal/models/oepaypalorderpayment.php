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
 * PayPal order payment class.
 */
class oePayPalOrderPayment extends oePayPalModel
{
    /**
     * Set PayPal order comment Id.
     *
     * @param string $sPaymentId
     */
    public function setId($sPaymentId)
    {
        $this->setPaymentId($sPaymentId);
    }

    /**
     * Set PayPal comment Id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getPaymentId();
    }

    /**
     * If Payment is valid.
     *
     * @var bool
     */
    protected $_blIsValid = true;

    /**
     * Payment comments
     *
     * @var array
     */
    protected $_oCommentList = null;


    /**
     * Set PayPal order comment Id.
     *
     * @param string $sPaymentId
     */
    public function setPaymentId($sPaymentId)
    {
        $this->_setValue('oepaypal_paymentid', $sPaymentId);
    }

    /**
     * Set PayPal comment Id.
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->_getValue('oepaypal_paymentid');
    }

    /**
     * Sets PayPal payment actions.
     *
     * @param string $sValue
     */
    public function setAction($sValue)
    {
        $this->_setValue('oepaypal_action', $sValue);
    }

    /**
     * Returns PayPal payment action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_getValue('oepaypal_action');
    }

    /**
     * Sets PayPal payment OrderId.
     *
     * @param string $sValue
     */
    public function setOrderId($sValue)
    {
        $this->_setValue('oepaypal_orderid', $sValue);
    }

    /**
     * Returns PayPal payment OrderId.
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->_getValue('oepaypal_orderid');
    }

    /**
     * Sets PayPal payment amount
     *
     * @param float $flValue
     */
    public function setAmount($flValue)
    {
        $this->_setValue('oepaypal_amount', $flValue);
    }

    /**
     * Returns PayPal payment amount.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->_getValue('oepaypal_amount');
    }

    /**
     * Set PayPal refunded amount.
     *
     * @param double $dAmount
     */
    public function setRefundedAmount($dAmount)
    {
        $this->_setValue('oepaypal_refundedamount', $dAmount);
    }

    /**
     * Adds given amount to PayPal refunded amount.
     *
     * @param double $dAmount
     */
    public function addRefundedAmount($dAmount)
    {
        $this->setRefundedAmount($dAmount + $this->getRefundedAmount());
    }

    /**
     * Get PayPal refunded amount
     *
     * @return string
     */
    public function getRefundedAmount()
    {
        return $this->_getValue('oepaypal_refundedamount');
    }

    /**
     * Returns not yet captured (remaining) order sum
     *
     * @return string
     */
    public function getRemainingRefundAmount()
    {
        $dAmount = $this->getAmount() - $this->getRefundedAmount();

        return sprintf("%.2f", round($dAmount, 2));
    }

    /**
     * Returns PayPal payment status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->_getValue('oepaypal_status');
    }

    /**
     * Returns PayPal payment status.
     *
     * @param string $sValue status
     */
    public function setStatus($sValue)
    {
        $this->_setValue('oepaypal_status', $sValue);
    }

    /**
     * Sets PayPal payment date.
     *
     * @param string $sValue
     */
    public function setDate($sValue)
    {
        $this->_setValue('oepaypal_date', $sValue);
    }

    /**
     * Returns PayPal payment date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->_getValue('oepaypal_date');
    }

    /**
     * Returns PayPal payment currency.
     *
     * @param string $sCurrency
     */
    public function setCurrency($sCurrency)
    {
        $this->_setValue('oepaypal_currency', $sCurrency);
    }

    /**
     * Sets PayPal payment currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->_getValue('oepaypal_currency');
    }

    /**
     * Set PayPal payment transaction id.
     *
     * @param string $sTransactionId
     */
    public function setTransactionId($sTransactionId)
    {
        $this->_setValue('oepaypal_transactionid', $sTransactionId);
    }

    /**
     *  Returns PayPal payment transaction id
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->_getValue('oepaypal_transactionid');
    }

    /**
     *  Set PayPal payment correlation id
     *
     * @param string $sCorrelationId
     */
    public function setCorrelationId($sCorrelationId)
    {
        $this->_setValue('oepaypal_correlationid', $sCorrelationId);
    }

    /**
     *  Returns PayPal payment correlation id
     *
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->_getValue('oepaypal_correlationid');
    }

    /**
     *  Load payment data by given transaction id
     *
     * @param string $sTransactionId transaction id
     *
     * @return bool
     */
    public function loadByTransactionId($sTransactionId)
    {
        $blResult = false;
        $aData = $this->_getDbGateway()->loadByTransactionId($sTransactionId);
        if ($aData) {
            $this->setData($aData);
            $blResult = true;
        }

        return $blResult;
    }

    /**
     * Sets if payment is valid.
     *
     * @param boolean $blIsValid payment is valid.
     */
    public function setIsValid($blIsValid)
    {
        $this->_blIsValid = (bool) $blIsValid;
    }

    /**
     * Gets if payment pass validation.
     *
     * @return boolean
     */
    public function getIsValid()
    {
        return $this->_blIsValid;
    }

    /**
     * Get comments
     *
     * @return array
     */
    public function getCommentList()
    {
        if (is_null($this->_oCommentList)) {
            $oComments = oxNew('oePayPalOrderPaymentCommentList');
            $oComments->load($this->getPaymentId());
            $this->setCommentList($oComments);
        }

        return $this->_oCommentList;
    }

    /**
     * Set comments.
     *
     * @param array $aComments
     */
    public function setCommentList($aComments)
    {
        $this->_oCommentList = $aComments;
    }

    /**
     * Add comment.
     *
     * @param oePaypalOrderPaymentComment $oComment comment
     */
    public function addComment($oComment)
    {
        $this->getCommentList()->addComment($oComment);
    }

    /**
     * Return database gateway.
     *
     * @return oePayPalOrderPaymentDbGateway
     */
    protected function _getDbGateway()
    {
        if (is_null($this->_oDbGateway)) {
            $this->_setDbGateway(oxNew('oePayPalOrderPaymentDbGateway'));
        }

        return $this->_oDbGateway;
    }
}
