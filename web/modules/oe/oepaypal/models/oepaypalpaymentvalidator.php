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
 * oePayPalPaymentValidator class for checking validation of PayPal payment for user and basket amount
 */
class oePayPalPaymentValidator
{

    /**
     * Basket price
     *
     * @var double $_dPrice
     */
    protected $_dPrice;

    /**
     * Config object
     *
     * @var oxConfig $_oConfig
     */
    protected $_oConfig = null;

    /**
     * User object
     *
     * @var oxUser $_oUser
     */
    protected $_oUser = null;

    /**
     * Payment object
     *
     * @var oxPayment $_oPayment
     */
    protected $_oPayment = null;

    /**
     * Check country in validator.
     *
     * @var bool
     */
    protected $_blCheckCountry = true;

    /**
     * Basket price setter
     *
     * @param double $dPrice
     */
    public function setPrice($dPrice)
    {
        $this->_dPrice = $dPrice;
    }

    /**
     * Basket price getter
     *
     * @return double
     */
    public function getPrice()
    {
        return $this->_dPrice;
    }

    /**
     * Config object setter
     *
     * @param oxConfig $oConfig
     */
    public function setConfig($oConfig)
    {
        $this->_oConfig = $oConfig;
    }

    /**
     * Config object getter
     *
     * @return oxConfig
     */
    public function getConfig()
    {
        return $this->_oConfig;
    }

    /**
     * User object setter
     *
     * @param oxUser $oUser
     */
    public function setUser($oUser)
    {
        $this->_oUser = $oUser;
    }

    /**
     * User object getter
     *
     * @return oxUser
     */
    public function getUser()
    {
        return $this->_oUser;
    }

    /**
     * Payment object setter
     *
     * @param oxPayment $oPayment
     */
    public function setPayment($oPayment)
    {
        $this->_oPayment = $oPayment;
    }

    /**
     * Check country setter.
     *
     * @param boolean $blCheckCountry
     */
    public function setCheckCountry($blCheckCountry)
    {
        $this->_blCheckCountry = $blCheckCountry;
    }

    /**
     * Returns if country should be checked.
     *
     * @return boolean
     */
    public function getCheckCountry()
    {
        return $this->_blCheckCountry;
    }

    /**
     * Payment object getter
     *
     * @return oxPayment
     */
    public function getPayment()
    {
        if (is_null($this->_oPayment)) {
            $oPayPalPayment = oxNew('oxPayment');
            $oPayPalPayment->load('oxidpaypal');
            $this->setPayment($oPayPalPayment);
        }

        return $this->_oPayment;
    }

    /**
     * Checks if PayPal payment is active
     *
     * @return boolean
     */
    public function isPaymentActive()
    {
        $blResult = false;
        if ($oPayPalPayment = $this->getPayment()) {
            $blResult = $oPayPalPayment->oxpayments__oxactive->value ? true : false;
        }

        return $blResult;
    }


    /**
     * Checks if payment is valid according to config, user and basket amount.
     *
     * @return boolean
     */
    public function isPaymentValid()
    {
        $blIsValid = $this->isPaymentActive();

        if ($blIsValid && !is_null($this->getPrice())) {
            $blIsValid = $this->_checkPriceRange() && $this->_checkMinOrderPrice();
        }

        $oUser = $this->getUser();
        if ($blIsValid && $oUser && $oUser->hasAccount()) {
            $blIsValid = $this->_checkUserGroup();
        }

        if ($blIsValid && $oUser && $this->getCheckCountry()) {
            $blIsValid = $this->_checkUserCountry();
        }

        return $blIsValid;
    }

    /**
     * Checks if basket price is inside payment price range
     * If range is not set check returns true
     *
     * @return bool
     */
    protected function _checkPriceRange()
    {
        $blIsValid = true;

        $oPayPalPayment = $this->getPayment();

        if ($oPayPalPayment->oxpayments__oxfromamount->value != 0 ||
            $oPayPalPayment->oxpayments__oxtoamount->value != 0
        ) {

            $oCur = $this->getConfig()->getActShopCurrencyObject();
            $dPrice = $this->getPrice() / $oCur->rate;

            $blIsValid = (($dPrice >= $oPayPalPayment->oxpayments__oxfromamount->value) &&
                          ($dPrice <= $oPayPalPayment->oxpayments__oxtoamount->value));
        }

        return $blIsValid;
    }

    /**
     * Checks if basket price is higher than minimum order price
     * If min price is not set check returns true
     *
     * @return bool
     */
    protected function _checkMinOrderPrice()
    {
        $blIsValid = true;

        if ($iMinOrderPrice = $this->getConfig()->getConfigParam('iMinOrderPrice')) {
            $blIsValid = $this->getPrice() > $iMinOrderPrice;
        }

        return $blIsValid;
    }

    /**
     * Checks if user country is among payment countries
     * If payment countries are not set returns true
     *
     * @return bool
     */
    protected function _checkUserCountry()
    {
        $blIsValid = true;

        $oPayPalPayment = $this->getPayment();

        $aCountries = $oPayPalPayment->getCountries();
        if ($aCountries) {
            $blIsValid = false;
            foreach ($aCountries as $sCountryId) {
                if ($sCountryId === $this->_getShippingCountryId()) {
                    $blIsValid = true;
                    break;
                }
            }
        }

        return $blIsValid;
    }

    /**
     * Checks if user belongs group that is assigned to payment
     * If payment does not have any groups assigned returns true
     *
     * @return bool
     */
    protected function _checkUserGroup()
    {
        $blIsValid = true;

        $oPayPalPayment = $this->getPayment();
        $oGroups = $oPayPalPayment->getGroups();

        if ($oGroups && $oGroups->count() > 0) {
            $blIsValid = $this->_isUserAssignedToGroup($oGroups);
        }

        return $blIsValid;
    }

    /**
     * Checks whether user is assigned to given groups array.
     *
     * @param oxList $oGroups
     *
     * @return bool
     */
    protected function _isUserAssignedToGroup($oGroups)
    {
        $blIsValid = false;

        $oUser = $this->getUser();
        foreach ($oGroups as $oGroup) {
            if ($oUser->inGroup($oGroup->getId())) {
                $blIsValid = true;
                break;
            }
        }

        return $blIsValid;
    }

    /**
     * Returns shipping country ID.
     *
     * @return string
     */
    protected function _getShippingCountryId()
    {
        $oUser = $this->getUser();
        if ($oUser->getSelectedAddressId()) {
            $sCountryId = $oUser->getSelectedAddress()->oxaddress__oxcountryid->value;
        } else {
            $sCountryId = $oUser->oxuser__oxcountryid->value;
        }

        return $sCountryId;
    }
}
