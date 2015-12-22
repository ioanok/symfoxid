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
 * PayPal order action void class
 */
class oePayPalOrderVoidAction extends oePayPalOrderAction
{
    /**
     * Processes PayPal response
     */
    public function process()
    {
        $oHandler = $this->getHandler();
        $oResponse = $oHandler->getPayPalResponse();
        $oData = $oHandler->getData();

        $oOrder = $this->getOrder();
        $dAmount = $oOrder->getRemainingOrderSum();
        $oOrder->setVoidedAmount($dAmount);
        $oOrder->setPaymentStatus($oData->getOrderStatus());
        $oOrder->save();

        $oPayment = oxNew('oePayPalOrderPayment');
        $oPayment->setDate($this->getDate());
        $oPayment->setTransactionId($oResponse->getAuthorizationId());
        $oPayment->setCorrelationId($oResponse->getCorrelationId());
        $oPayment->setAction('void');
        $oPayment->setStatus('Voided');
        $oPayment->setAmount($dAmount);

        $oPayment = $oOrder->getPaymentList()->addPayment($oPayment);

        if ($oData->getComment()) {
            $oComment = oxNew('oePayPalOrderPaymentComment');
            $oComment->setComment($oData->getComment());
            $oPayment->addComment($oComment);
        }
    }
}
