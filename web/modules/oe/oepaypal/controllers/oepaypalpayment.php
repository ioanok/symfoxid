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
 * Payment class wrapper for PayPal module
 */
class oePayPalPayment extends oePayPalPayment_parent
{
    /**
     * Detects is current payment must be processed by PayPal and instead of standard validation
     * redirects to standard PayPal dispatcher
     *
     * @return bool
     */
    public function validatePayment()
    {
        $sPaymentId = oxRegistry::getConfig()->getRequestParameter('paymentid');
        $oSession = $this->getSession();
        $oBasket = $oSession->getBasket();
        if ($sPaymentId === 'oxidpaypal' && !$this->isConfirmedByPayPal($oBasket)) {

            $oSession->setVariable('paymentid', 'oxidpaypal');

            if (oxRegistry::getConfig()->getRequestParameter('bltsprotection')) {
                $sTsProductId = oxRegistry::getConfig()->getRequestParameter('stsprotection');
                $oBasket->setTsProductId($sTsProductId);
                $oSession->setVariable('stsprotection', $sTsProductId);
            } else {
                $oSession->deleteVariable('stsprotection');
                $oBasket->setTsProductId(null);
            }

            return 'oePayPalStandardDispatcher?fnc=setExpressCheckout'
                   . '&displayCartInPayPal=' . ((int) oxRegistry::getConfig()->getRequestParameter('displayCartInPayPal'));
        }

        return parent::validatePayment();
    }

    /**
     * Detects if current payment was already successfully processed by PayPal
     *
     * @param oxBasket $oBasket basket object
     *
     * @return bool
     */
    public function isConfirmedByPayPal($oBasket)
    {
        $dOldBasketAmount = $this->getSession()->getVariable("oepaypal-basketAmount");
        if (!$dOldBasketAmount) {
            return false;
        }

        $oPayPalCheckValidator = oxNew("oePayPalCheckValidator");
        $oPayPalCheckValidator->setNewBasketAmount($oBasket->getPrice()->getBruttoPrice());
        $oPayPalCheckValidator->setOldBasketAmount($dOldBasketAmount);

        return $oPayPalCheckValidator->isPayPalCheckValid();
    }
}
