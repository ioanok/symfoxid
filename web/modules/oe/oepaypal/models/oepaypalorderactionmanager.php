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
 * PayPal order action manager class
 */
class oePayPalOrderActionManager
{
    /**
     * States related with transaction mode
     *
     * @var oePayPalPayPalOrder
     */
    protected $_aAvailableActions = array(
        'Sale'          => array(),
        'Authorization' => array('capture', 'reauthorize', 'void'),
    );

    /**
     * Order object
     *
     * @var oePayPalPayPalOrder
     */
    protected $_oOrder = null;

    /**
     * Sets order
     *
     * @param oePayPalPayPalOrder $oOrder
     */
    public function setOrder($oOrder)
    {
        $this->_oOrder = $oOrder;
    }

    /**
     * Returns order
     *
     * @return oePayPalPayPalOrder
     */
    public function getOrder()
    {
        return $this->_oOrder;
    }

    /**
     * Return state for given transaction mode
     *
     * @param string $sMode transaction mode
     *
     * @return array
     */
    protected function _getAvailableAction($sMode)
    {
        $aActions = $this->_aAvailableActions[$sMode];

        return $aActions ? $aActions : array();
    }

    /**
     * Checks whether action is available for given order
     *
     * @param string $sAction
     *
     * @return bool
     */
    public function isActionAvailable($sAction)
    {
        $oOrder = $this->getOrder();

        $aAvailableActions = $this->_getAvailableAction($oOrder->getTransactionMode());

        $blIsAvailable = in_array($sAction, $aAvailableActions);

        if ($blIsAvailable) {
            $blIsAvailable = false;

            switch ($sAction) {
                case 'capture':
                case 'reauthorize':
                case 'void':
                    if ($oOrder->getRemainingOrderSum() > 0 && $oOrder->getVoidedAmount() < $oOrder->getRemainingOrderSum()) {
                        $blIsAvailable = true;
                    }
                    break;
            }
        }

        return $blIsAvailable;
    }
}
