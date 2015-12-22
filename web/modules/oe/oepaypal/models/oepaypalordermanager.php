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
 * Class oePayPalOrderManager.
 */
class oePayPalOrderManager
{
    /**
     * @var oePayPalOrderPayment
     */
    protected $_oOrderPayment = null;

    /**
     * @var oePayPalPayPalOrder
     */
    protected $_oOrder = null;

    /**
     * @var oePayPalOrderPaymentStatusCalculator
     */
    protected $_oOrderPaymentStatusCalculator = null;

    /**
     * @var oePayPalOrderPaymentListCalculator
     */
    protected $_oOrderPaymentListCalculator = null;

    /**
     * Sets order payment.
     *
     * @param oePayPalOrderPayment $oOrderPayment
     */
    public function setOrderPayment($oOrderPayment)
    {
        $this->_oOrderPayment = $oOrderPayment;
    }

    /**
     * Returns order payment.
     *
     * @return oePayPalOrderPayment
     */
    public function getOrderPayment()
    {
        return $this->_oOrderPayment;
    }

    /**
     * Sets order.
     *
     * @param oePayPalPayPalOrder $oOrder
     */
    public function setOrder($oOrder)
    {
        $this->_oOrder = $oOrder;
    }

    /**
     * Create object oePayPalPayPalOrder.
     * If Order is not set, create order from Order Payment.
     *
     * @return object
     */
    public function getOrder()
    {
        if ($this->_oOrder === null) {
            $oOrderPayment = $this->getOrderPayment();
            $oOrder = $this->_getOrderFromPayment($oOrderPayment);
            $this->setOrder($oOrder);
        }

        return $this->_oOrder;
    }

    /**
     * Sets oePayPalOrderPaymentStatusCalculator.
     *
     * @param oePayPalOrderPaymentStatusCalculator $oOrderPaymentStatusCalculator
     */
    public function setOrderPaymentStatusCalculator($oOrderPaymentStatusCalculator)
    {
        $this->_oOrderPaymentStatusCalculator = $oOrderPaymentStatusCalculator;
    }

    /**
     * Returns oePayPalOrderPaymentStatusCalculator.
     *
     * @return oePayPalOrderPaymentStatusCalculator
     */
    public function getOrderPaymentStatusCalculator()
    {
        if (is_null($this->_oOrderPaymentStatusCalculator)) {
            $oOrderPaymentStatusCalculator = oxNew('oePayPalOrderPaymentStatusCalculator');
            $this->setOrderPaymentStatusCalculator($oOrderPaymentStatusCalculator);
        }

        return $this->_oOrderPaymentStatusCalculator;
    }

    /**
     * Sets oePayPalOrderPaymentListCalculator.
     *
     * @param oePayPalOrderPaymentListCalculator $oOrderPaymentListCalculator
     */
    public function setOrderPaymentListCalculator($oOrderPaymentListCalculator)
    {
        $this->_oOrderPaymentListCalculator = $oOrderPaymentListCalculator;
    }

    /**
     * Returns oePayPalOrderPaymentListCalculator.
     *
     * @return oePayPalOrderPaymentStatusCalculator
     */
    public function getOrderPaymentListCalculator()
    {
        if (is_null($this->_oOrderPaymentListCalculator)) {
            $oOrderPaymentListCalculator = oxNew('oePayPalOrderPaymentListCalculator');
            $this->setOrderPaymentListCalculator($oOrderPaymentListCalculator);
        }

        return $this->_oOrderPaymentListCalculator;
    }

    /**
     * Update order manager to status get from order status calculator.
     *
     * @return bool
     */
    public function updateOrderStatus()
    {
        $blOrderUpdated = false;
        $oOrder = $this->getOrder();

        if (!is_null($oOrder)) {
            $oOrderPayment = $this->getOrderPayment();
            $oOrder = $this->recalculateAmounts($oOrder);
            $sNewOrderStatus = $this->_calculateOrderStatus($oOrderPayment, $oOrder);
            $this->_updateOrderStatus($oOrder, $sNewOrderStatus);
            $blOrderUpdated = true;
        }

        return $blOrderUpdated;
    }

    /**
     * Recalculate order amounts from payment list.
     *
     * @param oePayPalPayPalOrder $order
     *
     * @return mixed
     */
    protected function recalculateAmounts($order)
    {
        $paymentList = $order->getPaymentList();
        $orderPaymentListCalculator = $this->getOrderPaymentListCalculator();
        $orderPaymentListCalculator->setPaymentList($paymentList);
        $orderPaymentListCalculator->calculate();

        $order->setCapturedAmount($orderPaymentListCalculator->getCapturedAmount());
        $order->setVoidedAmount($orderPaymentListCalculator->getVoidedAmount());
        $order->setRefundedAmount($orderPaymentListCalculator->getRefundedAmount());
        $order->save();

        return $order;
    }

    /**
     * Wrapper for order payment calculator.
     *
     * @param oePayPalOrderPayment $oOrderPayment Order payment to set to calculator.
     * @param oePayPalPayPalOrder  $oOrder        Order to be set to validator.
     *
     * @return null|string
     */
    protected function _calculateOrderStatus($oOrderPayment, $oOrder)
    {
        $oOrderPaymentStatusCalculator = $this->getOrderPaymentStatusCalculator();
        $oOrderPaymentStatusCalculator->setOrderPayment($oOrderPayment);
        $oOrderPaymentStatusCalculator->setOrder($oOrder);

        $sNewOrderStatus = $oOrderPaymentStatusCalculator->getStatus();

        return $sNewOrderStatus;
    }

    /**
     * Update order to given status.
     *
     * @param oePayPalPayPalOrder $oOrder          Order to be updated.
     * @param string              $sNewOrderStatus New order status.
     */
    protected function _updateOrderStatus($oOrder, $sNewOrderStatus)
    {
        $oOrder->setPaymentStatus($sNewOrderStatus);
        $oOrder->save();
    }

    /**
     * Load order by order id from order payment.
     *
     * @param oePayPalOrderPayment $oOrderPayment order payment to get order id.
     *
     * @return oePayPalPayPalOrder|null
     */
    protected function _getOrderFromPayment($oOrderPayment)
    {
        $sOrderId = null;
        $oOrder = null;
        if (!is_null($oOrderPayment)) {
            $sOrderId = $oOrderPayment->getOrderId();
        }
        if (!is_null($sOrderId)) {
            $oOrder = oxNew('oePayPalPayPalOrder');
            $oOrder->setOrderId($sOrderId);
            $oOrder->load();
        }

        return $oOrder;
    }
}
