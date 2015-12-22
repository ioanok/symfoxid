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
 * Class for calculation PayPal order statuses after IPN and order creations.
 * Also calculates statuses for suggestion on void, refund, capture operation on PayPal order.
 */
class oePayPalOrderPaymentStatusCalculator
{
    /**
     * PayPal Order.
     *
     * @var oePayPalPayPalOrder
     */
    protected $_oOrder = null;

    /**
     * @var oePayPalOrderPayment
     */
    protected $_oOrderPayment = null;

    /**
     * Set PayPal Order.
     *
     * @param oePayPalPayPalOrder $oOrder PayPal order
     */
    public function setOrder($oOrder)
    {
        $this->_oOrder = $oOrder;
    }

    /**
     * Return PayPal Order.
     *
     * @return oePayPalPayPalOrder
     */
    public function getOrder()
    {
        return $this->_oOrder;
    }

    /**
     * Sets PayPal OrderPayment.
     *
     * @param oePayPalOrderPayment $oOrderPayment
     */
    public function setOrderPayment($oOrderPayment)
    {
        $this->_oOrderPayment = $oOrderPayment;
    }

    /**
     * Return PayPal OrderPayment.
     *
     * @return oePayPalOrderPayment
     */
    public function getOrderPayment()
    {
        return $this->_oOrderPayment;
    }

    /**
     * Return status for suggestion on void operation.
     *
     * @return bool
     */
    protected function _getSuggestStatusOnVoid()
    {
        $sStatus = 'canceled';

        if ($this->getOrder()->getCapturedAmount() > 0) {
            $sStatus = 'completed';
        }

        return $sStatus;
    }

    /**
     * Return true if order statuses can be changed automatically.
     *
     * @return bool
     */
    protected function _isOrderPaymentStatusFinal()
    {
        $sOrderPaymentStatus = $this->getOrder()->getPaymentStatus();

        return $sOrderPaymentStatus == 'failed' || $sOrderPaymentStatus == 'canceled';
    }

    /**
     * Returns order payment status which should be set after order creation or IPN.
     *
     * @return string|null
     */
    public function getStatus()
    {
        if (is_null($this->getOrder())) {
            return;
        }

        $sStatus = $this->_getOrderPaymentStatusFinal();

        if (is_null($sStatus)) {
            $sStatus = $this->_getOrderPaymentStatusPaymentValid();
        }
        if (is_null($sStatus)) {
            $sStatus = $this->_getOrderPaymentStatusPayments();
        }

        return $sStatus;
    }

    /**
     * Returns order suggestion for payment status on given action and on given payment.
     *
     * @param string $sAction - action with order payment: void, refund, capture, refund_partial, capture_partial
     *
     * @return string|null
     */
    public function getSuggestStatus($sAction)
    {
        if (is_null($this->getOrder())) {
            return;
        }

        $sStatus = $this->_getOrderPaymentStatusPaymentValid();
        if (is_null($sStatus)) {
            $sStatus = $this->_getStatusByAction($sAction);
        }

        return $sStatus;
    }

    /**
     * Returns order payment status if order has final status.
     *
     * @return string|null
     */
    protected function _getOrderPaymentStatusFinal()
    {
        $sStatus = null;
        if ($this->_isOrderPaymentStatusFinal()) {
            $sStatus = $this->getOrder()->getPaymentStatus();
        }

        return $sStatus;
    }

    /**
     * Returns order payment status by checking if set payment is valid.
     *
     * @return string|null
     */
    protected function _getOrderPaymentStatusPaymentValid()
    {
        $sStatus = null;
        $oOrderPayment = $this->getOrderPayment();
        if (isset($oOrderPayment) && !$oOrderPayment->getIsValid()) {
            $sStatus = 'failed';
        }

        return $sStatus;
    }

    /**
     * Returns order payment status calculated from existing payments.
     *
     * @return string|null
     */
    protected function _getOrderPaymentStatusPayments()
    {
        $sStatus = 'completed';
        $oPaymentList = $this->getOrder()->getPaymentList();

        if ($oPaymentList->hasPendingPayment()) {
            $sStatus = 'pending';
        } elseif ($oPaymentList->hasFailedPayment()) {
            $sStatus = 'failed';
        }

        return $sStatus;
    }

    /**
     * Returns order suggestion for payment status on given action.
     *
     * @param string $sAction performed action.
     *
     * @return string
     */
    protected function _getStatusByAction($sAction)
    {
        $sStatus = null;
        switch ($sAction) {
            case 'void':
                $sStatus = $this->_getSuggestStatusOnVoid();
                break;
            case 'refund_partial':
            case 'reauthorize':
                $sStatus = $this->getOrder()->getPaymentStatus();
                break;
            case 'refund':
            case 'capture':
            case 'capture_partial':
                $sStatus = 'completed';
                break;
            default:
                $sStatus = 'completed';
        }

        return $sStatus;
    }
}
