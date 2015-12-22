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
class oePayPalOrder_PayPal extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxOrder object,
     * passes it's data to Smarty engine and returns
     * name of template file "order_paypal.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData["sOxid"] = $this->getEditObjectId();
        if ($this->isNewPayPalOrder()) {
            $this->_aViewData['oOrder'] = $this->getEditObject();
        } else {
            $this->_aViewData['sMessage'] = $this->isPayPalOrder() ? oxRegistry::getLang()->translateString("OEPAYPAL_ONLY_FOR_NEW_PAYPAL_PAYMENT") :
                oxRegistry::getLang()->translateString("OEPAYPAL_ONLY_FOR_PAYPAL_PAYMENT");
        }

        return "order_paypal.tpl";
    }

    /**
     * Processes PayPal actions.
     */
    public function processAction()
    {
        try {
            /** @var oePayPalRequest $oRequest */
            $oRequest = oxNew('oePayPalRequest');
            $sAction = $oRequest->getRequestParameter('action');

            $oOrder = $this->getEditObject();

            /** @var oePayPalOrderActionFactory $oActionFactory */
            $oActionFactory = oxNew('oePayPalOrderActionFactory', $oRequest, $oOrder);
            $oAction = $oActionFactory->createAction($sAction);

            $oAction->process();
        } catch (oxException $oException) {
            $this->_aViewData["error"] = $oException->getMessage();
        }
    }

    /**
     * Returns PayPal order action manager.
     *
     * @return oePayPalOrderActionManager
     */
    public function getOrderActionManager()
    {
        /** @var oePayPalOrderActionManager $oManager */
        $oManager = oxNew('oePayPalOrderActionManager');
        $oManager->setOrder($this->getEditObject()->getPayPalOrder());

        return $oManager;
    }

    /**
     * Returns PayPal order action manager
     *
     * @return oePayPalOrderPaymentActionManager
     */
    public function getOrderPaymentActionManager()
    {
        $oManager = oxNew('oePayPalOrderPaymentActionManager');

        return $oManager;
    }

    /**
     * Returns PayPal order action manager
     *
     * @return oePayPalOrderPaymentActionManager
     */
    public function getOrderPaymentStatusCalculator()
    {
        /** @var oePayPalOrderPaymentStatusCalculator $oStatusCalculator */
        $oStatusCalculator = oxNew('oePayPalOrderPaymentStatusCalculator');
        $oStatusCalculator->setOrder($this->getEditObject()->getPayPalOrder());

        return $oStatusCalculator;
    }

    /**
     * Returns PayPal order action manager
     *
     * @return oePayPalOrderPaymentActionManager
     */
    public function getOrderPaymentStatusList()
    {
        $oList = oxNew('oePayPalOrderPaymentStatusList');

        return $oList;
    }

    /**
     * Returns editable order object
     *
     * @return oePayPalOxOrder
     */
    public function getEditObject()
    {
        $soxId = $this->getEditObjectId();
        if ($this->_oEditObject === null && isset($soxId) && $soxId != '-1') {
            $this->_oEditObject = oxNew('oxOrder');
            $this->_oEditObject->load($soxId);
        }

        return $this->_oEditObject;
    }

    /**
     * Method checks if order was made with current PayPal module, but not eFire PayPal module
     *
     * @return bool
     */
    public function isNewPayPalOrder()
    {
        $blActive = false;

        $oOrder = $this->getEditObject();
        $oOrderPayPal = $oOrder->getPayPalOrder();
        if ($this->isPayPalOrder() && $oOrderPayPal->isLoaded()) {
            $blActive = true;
        }

        return $blActive;
    }

    /**
     * Method checks is order was made with any PayPal module
     *
     * @return bool
     */
    public function isPayPalOrder()
    {
        $blActive = false;

        $oOrder = $this->getEditObject();
        if ($oOrder && $oOrder->getFieldData('oxpaymenttype') == 'oxidpaypal') {
            $blActive = true;
        }

        return $blActive;
    }

    /**
     * Template getter for price formatting
     *
     * @param double $dPrice price
     *
     * @return string
     */
    public function formatPrice($dPrice)
    {
        return oxRegistry::getLang()->formatCurrency($dPrice);
    }
}