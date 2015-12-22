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
class oePayPalOrderActionFactory
{

    /**
     * @var oePayPalRequest
     */
    protected $_oRequest = null;

    /**
     * @var oePayPalOxOrder
     */
    protected $_oOrder = null;

    /**
     * Sets dependencies
     *
     * @param oePayPalRequest $oRequest
     * @param oePayPalOxOrder $oOrder
     */
    public function __construct($oRequest, $oOrder)
    {
        $this->_oRequest = $oRequest;
        $this->_oOrder = $oOrder;
    }

    /**
     * Returns Request object
     *
     * @return oePayPalRequest
     */
    public function getRequest()
    {
        return $this->_oRequest;
    }

    /**
     * Returns Order object
     *
     * @return oePayPalOxOrder
     */
    public function getOrder()
    {
        return $this->_oOrder;
    }

    /**
     * Creates action object by given action name.
     *
     * @param string $sAction
     *
     * @return object
     *
     * @throws oePayPalInvalidActionException
     */
    public function createAction($sAction)
    {
        $sMethod = "get" . ucfirst($sAction) . "Action";

        if (!method_exists($this, $sMethod)) {
            /** @var oePayPalInvalidActionException $oException */
            $oException = oxNew('oePayPalInvalidActionException');
            throw $oException;
        }

        return $this->$sMethod();
    }

    /**
     * Returns capture action object
     *
     * @return oePayPalOrderCaptureAction
     */
    public function getCaptureAction()
    {
        $oOrder = $this->getOrder();
        $oRequest = $this->getRequest();

        $oData = oxNew('oePayPalOrderCaptureActionData', $oRequest, $oOrder);
        $oHandler = oxNew('oePayPalOrderCaptureActionHandler', $oData);

        $oReauthorizeData = oxNew('oePayPalOrderReauthorizeActionData', $oRequest, $oOrder);
        $oReauthorizeHandler = oxNew('oePayPalOrderReauthorizeActionHandler', $oReauthorizeData);

        $oAction = oxNew('oePayPalOrderCaptureAction', $oHandler, $oOrder->getPayPalOrder(), $oReauthorizeHandler);

        return $oAction;
    }

    /**
     * Returns refund action object
     *
     * @return oePayPalOrderRefundAction
     */
    public function getRefundAction()
    {
        $oOrder = $this->getOrder();
        $oData = oxNew('oePayPalOrderRefundActionData', $this->getRequest(), $oOrder);
        $oHandler = oxNew('oePayPalOrderRefundActionHandler', $oData);

        $oAction = oxNew('oePayPalOrderRefundAction', $oHandler, $oOrder->getPayPalOrder());

        return $oAction;
    }

    /**
     * Returns void action object
     *
     * @return oePayPalOrderVoidAction
     */
    public function getVoidAction()
    {
        $oOrder = $this->getOrder();
        $oData = oxNew('oePayPalOrderVoidActionData', $this->getRequest(), $oOrder);
        $oHandler = oxNew('oePayPalOrderVoidActionHandler', $oData);

        $oAction = oxNew('oePayPalOrderVoidAction', $oHandler, $oOrder->getPayPalOrder());

        return $oAction;
    }
}
