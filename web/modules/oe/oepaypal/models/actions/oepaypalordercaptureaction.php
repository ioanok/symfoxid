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
 * PayPal order action capture class
 */
class oePayPalOrderCaptureAction extends oePayPalOrderAction
{

    /**
     * @var oePayPalOrderReauthorizeActionHandler
     */
    protected $_oReauthorizeHandler = null;

    /**
     * Sets dependencies.
     *
     * @param oePayPalOrderCaptureActionHandler     $oHandler
     * @param oePayPalPayPalOrder                   $oOrder
     * @param oePayPalOrderReauthorizeActionHandler $oReauthorizeHandler
     */
    public function __construct($oHandler, $oOrder, $oReauthorizeHandler)
    {
        parent::__construct($oHandler, $oOrder);

        $this->_oReauthorizeHandler = $oReauthorizeHandler;
    }

    /**
     * Returns reauthorize action handler.
     *
     * @return oePayPalOrderReauthorizeActionHandler
     */
    public function getReauthorizeHandler()
    {
        return $this->_oReauthorizeHandler;
    }

    /**
     * Processes PayPal response.
     */
    public function process()
    {
        $this->_reauthorize();

        $oHandler = $this->getHandler();

        $oResponse = $oHandler->getPayPalResponse();
        $oData = $oHandler->getData();

        $this->_updateOrder($oResponse, $oData);

        $oPayment = $this->_createPayment($oResponse);
        $oPaymentList = $this->getOrder()->getPaymentList();
        $oPayment = $oPaymentList->addPayment($oPayment);

        $this->_addComment($oPayment, $oData->getComment());
    }

    /**
     * Reauthorizes payment if order was captured at least once.
     */
    protected function _reauthorize()
    {
        $oOrder = $this->getOrder();

        if ($oOrder->getCapturedAmount() > 0) {
            $oHandler = $this->getReauthorizeHandler();
            try {
                $oResponse = $oHandler->getPayPalResponse();

                $oPayment = oxNew('oePayPalOrderPayment');
                $oPayment->setDate($this->getDate());
                $oPayment->setTransactionId($oResponse->getAuthorizationId());
                $oPayment->setCorrelationId($oResponse->getCorrelationId());
                $oPayment->setAction('re-authorization');
                $oPayment->setStatus($oResponse->getPaymentStatus());

                $oOrder->getPaymentList()->addPayment($oPayment);
            } catch (oePayPalResponseException $e) {
                // Ignore PayPal response exceptions
            }
        }
    }

    /**
     * Updates order with PayPal response info.
     *
     * @param object $oResponse
     * @param object $oData
     */
    protected function _updateOrder($oResponse, $oData)
    {
        $oOrder = $this->getOrder();
        $oOrder->addCapturedAmount($oResponse->getCapturedAmount());
        $oOrder->setPaymentStatus($oData->getOrderStatus());
        $oOrder->save();
    }

    /**
     * Creates Payment object with PayPal response data.
     *
     * @param object $oResponse
     *
     * @return oePayPalOrderPayment
     */
    protected function _createPayment($oResponse)
    {
        $oPayment = oxNew('oePayPalOrderPayment');
        $oPayment->setDate($this->getDate());
        $oPayment->setTransactionId($oResponse->getTransactionId());
        $oPayment->setCorrelationId($oResponse->getCorrelationId());
        $oPayment->setAction('capture');
        $oPayment->setStatus($oResponse->getPaymentStatus());
        $oPayment->setAmount($oResponse->getCapturedAmount());
        $oPayment->setCurrency($oResponse->getCurrency());

        return $oPayment;
    }

    /**
     * Adds comment to given Payment object.
     *
     * @param object $oPayment
     * @param string $sComment
     */
    protected function _addComment($oPayment, $sComment)
    {
        if ($sComment) {
            $oComment = oxNew('oePayPalOrderPaymentComment');
            $oComment->setComment($sComment);
            $oPayment->addComment($oComment);
        }
    }
}
