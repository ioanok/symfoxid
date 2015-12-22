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
 * @version   OXID eShop PE
 */

/**
 * Payment gateway manager.
 * Checks and sets payment method data, executes payment.
 *
 */

class oxPaymentGateway extends oxSuperCfg
{

    /**
     * Payment status (active - true/not active - false) (default false).
     *
     * @var bool
     */
    protected $_blActive = false;

    /**
     * oUserpayment object (default null).
     *
     * @var object
     */
    protected $_oPaymentInfo = null;

    /**
     * Last error nr. For backward compatibility must be >3
     *
     * @abstract
     * @var string
     */
    protected $_iLastErrorNo = 4;

    /**
     * Last error text.
     *
     * @abstract
     * @var string
     */
    protected $_sLastError = null;

    /**
     * Sets payment parameters.
     *
     * @param object $oUserpayment User payment object
     */
    public function setPaymentParams($oUserpayment)
    {
        // store data
        $this->_oPaymentInfo = & $oUserpayment;
    }

    /**
     * Executes payment, returns true on success.
     *
     * @param double $dAmount Goods amount
     * @param object &$oOrder User ordering object
     *
     * @return bool
     */
    public function executePayment($dAmount, & $oOrder)
    {
        $this->_iLastErrorNo = null;
        $this->_sLastError = null;

        if (!$this->_isActive()) {
            return true; // fake yes
        }

        // proceed with no payment
        // used for other countries
        if (@$this->_oPaymentInfo->oxuserpayments__oxpaymentsid->value == 'oxempty') {
            return true;
        }

        return false;
    }

    /**
     * Returns last payment processing error nr.
     *
     * @return int
     */
    public function getLastErrorNo()
    {
        return $this->_iLastErrorNo;
    }

    /**
     * Returns last payment processing error.
     *
     * @return int
     */
    public function getLastError()
    {
        return $this->_sLastError;
    }

    /**
     * Returns true is payment active.
     *
     * @return bool
     */
    protected function _isActive()
    {
        return $this->_blActive;
    }
}
