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
 * PayPal Current item Article validator class.
 */
class oePayPalArticleToExpressCheckoutValidator
{
    /**
     * Item that will be validated.
     *
     * @var object
     */
    protected $_oItemToValidate;

    /**
     * User basket
     *
     * @var object
     */
    protected $_oBasket;

    /**
     *Sets current item of details page.
     *
     * @param object $oItemToValidate
     */
    public function setItemToValidate($oItemToValidate)
    {
        $this->_oItemToValidate = $oItemToValidate;
    }

    /**
     * Returns details page current item.
     *
     * @return oePayPalArticleToExpressCheckoutCurrentItem
     */
    public function getItemToValidate()
    {
        return $this->_oItemToValidate;
    }

    /**
     * Method sets basket object.
     *
     * @param oxBasket $oBasket
     */
    public function setBasket($oBasket)
    {
        $this->_oBasket = $oBasket;
    }

    /**
     * Methods returns basket object.
     *
     * @return oxBasket
     */
    public function getBasket()
    {
        return $this->_oBasket;
    }

    /**
     * Method returns if article valid
     *
     * @return bool
     */
    public function isArticleValid()
    {
        $blValid = true;
        if ($this->_isArticleAmountZero() || $this->_isSameItemInBasket()) {
            $blValid = false;
        }

        return $blValid;
    }

    /**
     * Check if same article is in basket.
     *
     * @return bool
     */
    protected function _isSameItemInBasket()
    {
        $aBasketContents = $this->getBasket()->getContents();
        foreach ($aBasketContents as $oBasketItem) {
            if ($this->_isArticleParamsEqual($oBasketItem)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if Article params equals with current items params.
     *
     * @param oxBasketItem $oBasketItem
     *
     * @return bool
     */
    protected function _isArticleParamsEqual($oBasketItem)
    {
        return ($oBasketItem->getProductId() == $this->getItemToValidate()->getArticleId() &&
                $oBasketItem->getPersParams() == $this->getItemToValidate()->getPersistParam() &&
                $oBasketItem->getSelList() == $this->getItemToValidate()->getSelectList());
    }

    /**
     * Checks if article amount 0.
     *
     * @return bool
     */
    protected function _isArticleAmountZero()
    {
        $iArticleAmount = $this->getItemToValidate()->getArticleAmount();

        return 0 == $iArticleAmount;
    }
}
