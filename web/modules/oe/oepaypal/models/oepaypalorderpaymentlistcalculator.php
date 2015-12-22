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
 * Class oePayPalOrderPaymentListCalculator
 */
class oePayPalOrderPaymentListCalculator
{
    /** @var oePayPalPaymentList */
    protected $paymentList = null;

    /** @var float Amount of void action. */
    protected $voidedAmount = 0.0;

    /** @var float Amount of voided authorization action. */
    protected $voidedAuthAmount = 0.0;

    /**@var float Amount of capture action. */
    protected $capturedAmount = 0.0;

    /** @var float Amount of refund action. */
    protected $refundedAmount = 0.0;

    /** @var array Payment and status match. */
    protected $paymentMatch = array(
        'capturedAmount' => array(
            'action' => array('capture'),
            'status' => array('Completed')
        ),
        'refundedAmount' => array(
            'action' => array('refund'),
            'status' => array('Refunded')
        ),
        'voidedAuthAmount'   => array(
            'action' => array('authorization'),
            'status' => array('Voided')
        ),
        'voidedAmount'   => array(
            'action' => array('void'),
            'status' => array('Voided')
        )
    );

    /**
     * Sets order.
     *
     * @param oePayPalOrderPaymentList $paymentList
     */
    public function setPaymentList($paymentList)
    {
        $this->paymentList = $paymentList;
    }

    /**
     * Sum up payment amounts for capture, void, refund.
     * Take into account successful transactions only.
     *
     * @return null
     */
    public function calculate()
    {
        if (!is_a($this->paymentList, 'oePayPalOrderPaymentList')) {
            return;
        }
        foreach (array_keys($this->paymentMatch) as $target) {
            $this->$target = 0.0;
        }
        foreach($this->paymentList as $payment) {
            $status = $payment->getStatus();
            $action = $payment->getAction();
            $amount = $payment->getAmount();

            foreach ($this->paymentMatch as $target => $check) {
                if ( in_array( $action, $check['action']) && in_array($status, $check['status']) ) {
                    $this->$target += $amount;
                }
            }
        }
    }

    /**
     * Getter for captured amount
     *
     * @return float
     */
    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    /**
     * Getter for refunded amount
     *
     * @return float
     */
    public function getRefundedAmount()
    {
        return $this->refundedAmount;
    }

    /**
     * Getter for voided amount.
     *
     * @return float
     */
    public function getVoidedAmount()
    {
        $return = 0.0;

        if (0 < $this->voidedAmount) {
            //void action is only logged when executed via shop admin
            $return =  $this->voidedAmount;
        } elseif (0 < $this->voidedAuthAmount) {
            //no data from void actions means we might have a voided Authorization
            $return = $this->voidedAuthAmount - $this->capturedAmount;
        }
        return $return;
    }

}
