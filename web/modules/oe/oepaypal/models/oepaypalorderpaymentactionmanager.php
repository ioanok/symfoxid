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
 * PayPal Order payment action manager class
 */
class oePayPalOrderPaymentActionManager
{

    /**
     * Array of available actions for payment action.
     *
     * @var array
     */
    protected $_availableActions = array(
        "capture" => array(
            "Completed" => array(
                'refund'
            )
        )
    );

    /**
     * Order object.
     *
     * @var oePayPalOrderPayment
     */
    protected $_oPayment = null;

    /**
     * Sets order.
     *
     * @param oePayPalOxOrder $oPayment
     */
    public function setPayment($oPayment)
    {
        $this->_oPayment = $oPayment;
    }

    /**
     * Returns order.
     *
     * @return oePayPalOrderPayment
     */
    public function getPayment()
    {
        return $this->_oPayment;
    }

    /**
     * Returns available actions for given payment action
     *
     * @param string $sPaymentAction
     * @param string $sPaymentStatus
     *
     * @return array
     */
    protected function _getAvailableActions($sPaymentAction, $sPaymentStatus)
    {
        $aActions = $this->_availableActions[$sPaymentAction][$sPaymentStatus];

        return $aActions ? $aActions : array();
    }


    /**
     * Checks whether action is available for given order
     *
     * @param string $sAction
     * @param object $oPayment
     *
     * @return bool
     */
    public function isActionAvailable($sAction, $oPayment = null)
    {
        if ($oPayment) {
            $this->setPayment($oPayment);
        }

        $oPayment = $this->getPayment();

        $blIsAvailable = in_array($sAction, $this->_getAvailableActions($oPayment->getAction(), $oPayment->getStatus()));

        if ($blIsAvailable) {
            $blIsAvailable = false;

            switch ($sAction) {
                case 'refund':
                    $blIsAvailable = ($oPayment->getAmount() > $oPayment->getRefundedAmount());
                    break;
            }
        }

        return $blIsAvailable;
    }
}
