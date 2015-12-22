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
 * PayPal Wrapping class
 */
class oePayPalWrapping extends oePayPalWrapping_parent
{
    /**
     * Checks if payment action is processed by PayPal
     *
     * @return bool
     */
    public function isPayPal()
    {
        return ($this->getSession()->getVariable("paymentid") == "oxidpaypal") ? true : false;
    }

    /**
     * Detects is current payment must be processed by PayPal and instead of standard validation
     * redirects to standard PayPal dispatcher
     *
     * @return bool
     */
    public function changeWrapping()
    {
        $sReturn = parent::changeWrapping();

        // in case user adds wrapping, basket info must be resubmitted..
        if ($this->isPayPal()) {
            $iPayPalType = (int) $this->getSession()->getVariable("oepaypal");

            if ($iPayPalType == 1) {
                $sReturn = "payment";
            } else {
                $sReturn = "basket";
            }
        }

        return $sReturn;
    }
}
