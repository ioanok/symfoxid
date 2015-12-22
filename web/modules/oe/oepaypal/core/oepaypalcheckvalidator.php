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
 * Validates changed basket amount. Checks if it is bigger than previous price.
 * Than returns false to recheck new basket amount in PayPal.
 */
class oePayPalCheckValidator
{
    /**
     * Basket new amount
     *
     * @var double
     */
    protected $_dNewBasketAmount = null;

    /**
     * Basket old amount
     *
     * @var double
     */
    protected $_dOldBasketAmount = null;

    /**
     * Returns if order should be rechecked by PayPal
     *
     * @return bool
     */
    public function isPayPalCheckValid()
    {
        $dNewBasketAmount = $this->getNewBasketAmount();
        $dPrevBasketAmount = $this->getOldBasketAmount();
        // check only if new price is different and bigger than old price
        if ($dNewBasketAmount > $dPrevBasketAmount) {
            return false;
        }

        return true;
    }

    /**
     * Sets new basket amount
     *
     * @param double $dNewBasketAmount changed basket amount
     */
    public function setNewBasketAmount($dNewBasketAmount)
    {
        $this->_dNewBasketAmount = $dNewBasketAmount;
    }

    /**
     * Returns new basket amount
     *
     * @return double
     */
    public function getNewBasketAmount()
    {
        return (float) $this->_dNewBasketAmount;
    }

    /**
     * Sets old basket amount
     *
     * @param double $dOldBasketAmount old basket amount
     */
    public function setOldBasketAmount($dOldBasketAmount)
    {
        $this->_dOldBasketAmount = $dOldBasketAmount;
    }

    /**
     * Returns old basket amount
     *
     * @return double
     */
    public function getOldBasketAmount()
    {
        return (float) $this->_dOldBasketAmount;
    }
}
