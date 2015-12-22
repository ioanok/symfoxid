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
 * PayPal out of stock validator class
 */
class oePayPalOutOfStockValidator
{

    /**
     * Basket object
     *
     * @var object
     */
    private $_oBasket;

    /**
     * Level of empty stock level
     *
     * @var int
     */
    private $_iEmptyStockLevel;

    /**
     * Sets empty stock level.
     *
     * @param int $iEmptyStockLevel
     */
    public function setEmptyStockLevel($iEmptyStockLevel)
    {
        $this->_iEmptyStockLevel = $iEmptyStockLevel;
    }

    /**
     * Returns empty stock level.
     *
     * @return int
     */
    public function getEmptyStockLevel()
    {
        return $this->_iEmptyStockLevel;
    }

    /**
     * Sets basket object.
     *
     * @param object $oBasket
     */
    public function setBasket($oBasket)
    {
        $this->_oBasket = $oBasket;
    }

    /**
     * Returns basket object.
     *
     * @return object
     */
    public function getBasket()
    {
        return $this->_oBasket;
    }

    /**
     * Checks if basket has Articles that are out of stock.
     *
     * @return bool
     */
    public function hasOutOfStockArticles()
    {
        $blResult = false;

        $aBasketContents = $this->getBasket()->getContents();

        foreach ($aBasketContents as $oBasketItem) {
            $oArticle = $oBasketItem->getArticle();
            if (($oArticle->getStockAmount() - $oBasketItem->getAmount()) < $this->getEmptyStockLevel()) {
                $blResult = true;
                break;
            }
        }

        return $blResult;
    }
}
