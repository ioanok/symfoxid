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
 * Article interface
 *
 */
interface oxIArticle
{

    /**
     * Checks if stock configuration allows to buy user chosen amount $dAmount
     *
     * @param double $dAmount         buyable amount
     * @param double $dArtStockAmount stock amount
     *
     * @return mixed
     */
    public function checkForStock($dAmount, $dArtStockAmount = 0);

    /**
     * Returns all selectlists this article has.
     *
     * @param string $sKeyPrefix Optionall key prefix
     *
     * @return array
     */
    public function getSelectLists($sKeyPrefix = null);

    /**
     * Creates, calculates and returns oxprice object for basket product.
     *
     * @param double $dAmount  Amount
     * @param string $aSelList Selection list
     * @param object $oBasket  User shopping basket object
     *
     * @return oxPrice
     */
    public function getBasketPrice($dAmount, $aSelList, $oBasket);

    /**
     * Checks if discount should be skipped for this article in basket. Returns true if yes.
     *
     * @return bool
     */
    public function skipDiscounts();

    /**
     * Returns ID's of categories. where this article is assigned
     *
     * @param bool $blActCats   select categories if all parents are active
     * @param bool $blSkipCache Whether to skip cache
     *
     * @return array
     */
    public function getCategoryIds($blActCats = false, $blSkipCache = false);

    /**
     * Calculates and returns price of article (adds taxes and discounts).
     *
     * @return oxPrice
     */
    public function getPrice();

    /**
     * Returns product id (oxid)
     *
     * @return string
     */
    public function getProductId();

    /**
     * Returns base article price from database
     *
     * @param double $dAmount article amount. Default is 1
     *
     * @return double
     */
    public function getBasePrice($dAmount = 1);

    /**
     * Returns true if object is derived from oxorderarticle class
     *
     * @return bool
     */
    public function isOrderArticle();

}
