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
 * Order class wrapper for PayPal module
 */
class oePayPalOrder extends oePayPalOrder_parent
{
    /**
     * Checks if payment action is processed by PayPal
     *
     * @return bool
     */
    public function isPayPal()
    {
        return ($this->getSession()->getVariable("paymentid") == "oxidpaypal");
    }

    /**
     * Returns PayPal user
     *
     * @return oxUser
     */
    public function getUser()
    {
        $oUser = parent::getUser();

        $sUserId = $this->getSession()->getVariable("oepaypal-userId");
        if ($this->isPayPal() && $sUserId) {
            $oPayPalUser = oxNew("oxUser");
            if ($oPayPalUser->load($sUserId)) {
                $oUser = $oPayPalUser;
            }
        }

        return $oUser;
    }

    /**
     * Returns PayPal payment object if PayPal is on, or returns parent::getPayment()
     *
     * @return oxPayment
     */
    public function getPayment()
    {
        if (!$this->isPayPal()) {
            // removing PayPal payment type from session
            $this->getSession()->deleteVariable('oepaypal');
            $this->getSession()->deleteVariable('oepaypal-basketAmount');

            return parent::getPayment();
        }

        if ($this->_oPayment === null) {
            // payment is set ?
            $oPayment = oxNew('oxPayment');
            if ($oPayment->load('oxidpaypal')) {
                $this->_oPayment = $oPayment;
            }
        }

        return $this->_oPayment;
    }

    /**
     * Returns current order object
     *
     * @return oxOrder
     */
    protected function _getOrder()
    {
        $oOrder = oxNew("oxOrder");
        $oOrder->load($this->getSession()->getVariable('sess_challenge'));

        return $oOrder;
    }

    /**
     * Checks if order payment is PayPal and redirects to payment processing part.
     *
     * @param int $iSuccess order state
     *
     * @return string
     */
    protected function _getNextStep($iSuccess)
    {
        $sNextStep = parent::_getNextStep($iSuccess);

        // Detecting PayPal & loading order & execute payment only if go wrong
        if ($this->isPayPal() && ($iSuccess == oxOrder::ORDER_STATE_PAYMENTERROR)) {

            $iPayPalType = (int) $this->getSession()->getVariable("oepaypal");
            $sNextStep = ($iPayPalType == 2) ? "basket" : "order";
        }

        return $sNextStep;
    }
}
